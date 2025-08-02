<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Prediksi;
use Illuminate\Support\Facades\View;

class PrediksiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $mahasiswas = collect();

        if ($user->role === 'admin') {
            $mahasiswas = Mahasiswa::with('user')
                ->get()
                ->map(function ($mhs) {
                    return (object)[
                        'id_mahasiswa' => $mhs->id_mahasiswa,
                        'nama' => $mhs->user->nama ?? '-',
                        'nim' => $mhs->user->nip_nim ?? '-',
                    ];
                });
        } elseif ($user->role === 'DPA') {
            $mahasiswas = Mahasiswa::with('user', 'dpa')
                ->whereHas('dpa', function ($q) use ($user) {
                    $q->where('id_user_dosen_pembimbing', $user->id_user);
                })
                ->get()
                ->map(function ($mhs) {
                    return (object)[
                        'id_mahasiswa' => $mhs->id_mahasiswa,
                        'nama' => $mhs->user->nama ?? '-',
                        'nim' => $mhs->user->nip_nim ?? '-',
                    ];
                });
        } elseif ($user->role === 'kaprodi') {
            $mahasiswas = Mahasiswa::with('user')
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('id_prodi', $user->id_prodi);
                })
                ->get()
                ->map(function ($mhs) {
                    return (object)[
                        'id_mahasiswa' => $mhs->id_mahasiswa,
                        'nama' => $mhs->user->nama ?? '-',
                        'nim' => $mhs->user->nip_nim ?? '-',
                    ];
                });
        }

        $hasil = null;
        if ($request->filled('mahasiswa_id')) {
            $hasil = Mahasiswa::with('user')->find($request->mahasiswa_id);
        }
        return view('prediksi.index', compact('mahasiswas', 'hasil'));
    }

    public function show($id)
    {
        $user = Auth::user();
        // Jika mahasiswa, hanya boleh akses detail dirinya sendiri
        if ($user->role === 'mahasiswa') {
            $mahasiswaUser = Mahasiswa::where('id_user', $user->id_user)->first();
            if ($mahasiswaUser && $mahasiswaUser->id_mahasiswa != $id) {
                return redirect()->route('prediksi.show', $mahasiswaUser->id_mahasiswa);
            }
        }
        $mahasiswa = Mahasiswa::with([
            'user.prodi',
            'angkatan',
            'dpa.dosenPembimbing',
            'riwayatAkademik',
        ])->findOrFail($id);

        // Ambil riwayat prediksi terbaru
        $riwayatPrediksi = \App\Models\Prediksi::with('user')
            ->where('id_mahasiswa', $id)
            ->orderByDesc('tanggal_prediksi')
            ->take(10)
            ->get();

        return view('prediksi.detail', compact('mahasiswa', 'riwayatPrediksi'));
    }

    public function formIntervensi($id)
    {
        return view('prediksi.form_intervensi');
    }

    public function predict(Request $request, $id)
    {
        $user = Auth::user();
        // Pembatasan khusus mahasiswa: maksimal 3x per hari
        if ($user->role === 'mahasiswa') {
            $countToday = Prediksi::where('id_mahasiswa', function ($q) use ($user) {
                $q->select('id_mahasiswa')
                    ->from('mahasiswa')
                    ->where('id_user', $user->id_user)
                    ->limit(1);
            })
                ->where('id_user', $user->id_user) // hanya prediksi yang dilakukan oleh mahasiswa itu sendiri
                ->whereDate('tanggal_prediksi', now()->toDateString())
                ->count();
            if ($countToday >= 3) {
                $message = 'Batas prediksi harian tercapai (maksimal 3x per hari). Silakan coba lagi besok.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 429);
                }
                return redirect()->route('prediksi.show', $id)->with('error', $message);
            }
        }
        $mahasiswa = Mahasiswa::with('riwayatAkademik', 'user.prodi')->findOrFail($id);
        $riwayat = $mahasiswa->riwayatAkademik;

        $body = [
            "ips_1" => (float) ($riwayat->ips_semester_1 ?? 0),
            "ips_2" => (float) ($riwayat->ips_semester_2 ?? 0),
            "ips_3" => (float) ($riwayat->ips_semester_3 ?? 0),
            "ips_4" => (float) ($riwayat->ips_semester_4 ?? 0),
            "cuti_1" => strtolower($riwayat->status_semester_1 ?? 'aktif'),
            "cuti_2" => strtolower($riwayat->status_semester_2 ?? 'aktif'),
            "cuti_3" => strtolower($riwayat->status_semester_3 ?? 'aktif'),
            "cuti_4" => strtolower($riwayat->status_semester_4 ?? 'aktif'),
            "total_sks_ditempuh" => (int) ($riwayat->total_sks_ditempuh ?? 0),
            "total_sks_tidak_lulus" => (int) ($riwayat->total_sks_tidak_lulus ?? 0),
        ];

        $apiUrl = 'https://ml-model-api-388345422624.asia-southeast1.run.app/predict';
        $response = Http::post($apiUrl, $body);

        if ($response->successful()) {
            $result = $response->json();

            // Jika role mahasiswa, JANGAN simpan ke database (aktifkan baris di bawah jika ingin simpan)
            // if ($user->role === 'mahasiswa') {
            //     // Prediksi::create([
            //     //     'id_mahasiswa'      => $mahasiswa->id_mahasiswa,
            //     //     'id_user'           => $user->id_user,
            //     //     'tanggal_prediksi'  => now(),
            //     //     'hasil_prediksi'    => $result['prediction'],
            //     //     'confidence_score'  => $result['confidence_score'],
            //     // ]);
            //     if ($request->ajax() || $request->wantsJson()) {
            //         return response()->json(['success' => true, 'data' => $result]);
            //     }
            //     return redirect()->route('prediksi.show', $id)->with('hasil_prediksi', $result);
            // }


            // Simpan ke database untuk semua role
            Prediksi::create([
                'id_mahasiswa'      => $mahasiswa->id_mahasiswa,
                'id_user'           => $user->id_user,
                'tanggal_prediksi'  => now(),
                'hasil_prediksi'    => $result['prediction'],
                'confidence_score'  => $result['confidence_score'],
            ]);

            // Ambil riwayat terbaru
            $riwayatPrediksi = Prediksi::with('user')
                ->where('id_mahasiswa', $id)
                ->orderByDesc('tanggal_prediksi')
                ->take(10)
                ->get();
            $riwayatHtml = View::make('prediksi.riwayatprediksi', [
                'riwayatPrediksi' => $riwayatPrediksi,
                'mahasiswa' => $mahasiswa
            ])->render();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'data' => $result, 'riwayat_html' => $riwayatHtml]);
            }
            return redirect()->route('prediksi.show', $id)->with('hasil_prediksi', $result);
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memproses prediksi. Silakan coba lagi.'], 500);
            }
            return redirect()->route('prediksi.show', $id)->with('error', 'Gagal memproses prediksi. Silakan coba lagi.');
        }
    }

    // Endpoint untuk AJAX refresh riwayat prediksi
    public function riwayatAjax($id)
    {
        $mahasiswa = Mahasiswa::with('user.prodi')->findOrFail($id);
        $riwayatPrediksi = Prediksi::with('user')
            ->where('id_mahasiswa', $id)
            ->orderByDesc('tanggal_prediksi')
            ->take(10)
            ->get();
        $riwayatHtml = View::make('prediksi.riwayatprediksi', [
            'riwayatPrediksi' => $riwayatPrediksi,
            'mahasiswa' => $mahasiswa
        ])->render();
        return response()->json(['riwayat_html' => $riwayatHtml]);
    }

    // Endpoint untuk AJAX refresh riwayat intervensi (notifikasi)
    public function riwayatIntervensiAjax($id)
    {
        $mahasiswa = Mahasiswa::with('user.prodi')->findOrFail($id);
        $riwayatNotifikasi = \App\Models\Notifikasi::with('user')
            ->where('id_mahasiswa', $id)
            ->orderByDesc('waktu_kirim')
            ->take(10)
            ->get();
        $riwayatHtml = View::make('prediksi.riwayatintervensi', [
            'riwayatNotifikasi' => $riwayatNotifikasi,
            'mahasiswa' => $mahasiswa
        ])->render();
        return response()->json(['riwayat_html' => $riwayatHtml]);
    }
}
