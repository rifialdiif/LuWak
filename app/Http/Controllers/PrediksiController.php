<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Prediksi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class PrediksiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Jika user adalah mahasiswa, langsung arahkan ke halaman detail mereka sendiri
        if ($user->role === 'mahasiswa') {
            $mahasiswaUser = Mahasiswa::where('id_user', $user->id_user)->first();
            if ($mahasiswaUser) {
                return redirect()->route('prediksi.show', $mahasiswaUser->id_mahasiswa);
            } else {
                // Jika mahasiswa tidak ditemukan, tampilkan pesan error
                return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan. Silakan hubungi admin.');
            }
        }

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

        // Validasi apakah mahasiswa memiliki data riwayat akademik
        if (!$mahasiswa->riwayatAkademik) {
            // Untuk semua role, tetap tampilkan halaman detail tapi dengan flag
            $showRiwayatModal = true;
            $riwayatPrediksi = collect(); // Empty collection
            $riwayatNotifikasi = collect(); // Empty collection
            return view('prediksi.detail', compact('mahasiswa', 'riwayatPrediksi', 'riwayatNotifikasi', 'showRiwayatModal'));
        }

        // Ambil riwayat prediksi terbaru
        $riwayatPrediksi = \App\Models\Prediksi::with('user')
            ->where('id_mahasiswa', $id)
            ->orderByDesc('tanggal_prediksi')
            ->take(10)
            ->get();

        // Ambil riwayat notifikasi terbaru
        $riwayatNotifikasi = \App\Models\Notifikasi::with('user')
            ->where('id_mahasiswa', $id)
            ->orderByDesc('waktu_kirim')
            ->take(10)
            ->get();

        return view('prediksi.detail', compact('mahasiswa', 'riwayatPrediksi', 'riwayatNotifikasi'));
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

        // Validasi apakah mahasiswa memiliki data riwayat akademik
        if (!$riwayat) {
            $message = 'Mahasiswa tidak memiliki data riwayat akademik. Silakan hubungi admin untuk mengisi data riwayat akademik.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->route('prediksi.show', $id)->with('error', $message);
        }

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

        $apiUrl = 'http://127.0.0.1:5000/predict';

        try {
            $response = Http::timeout(30)->post($apiUrl, $body);

            if ($response->successful()) {
                $result = $response->json();

                // Validasi response dari API
                if (!isset($result['prediction']) || !isset($result['confidence_score'])) {
                    $errorMessage = 'Response dari server prediksi tidak valid. Silakan coba lagi atau hubungi administrator.';
                    Log::warning('Invalid API response structure', [
                        'mahasiswa_id' => $id,
                        'user_id' => $user->id_user,
                        'response' => $result
                    ]);

                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => $errorMessage], 500);
                    }
                    return redirect()->route('prediksi.show', $id)->with('error', $errorMessage);
                }

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
                // Handle HTTP error responses
                $errorMessage = $this->getHttpErrorMessage($response->status());
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMessage], $response->status());
                }
                return redirect()->route('prediksi.show', $id)->with('error', $errorMessage);
            }
        } catch (ConnectionException $e) {
            // Handle connection errors (no internet, DNS issues, etc.)
            Log::warning('Prediksi connection error: ' . $e->getMessage(), [
                'mahasiswa_id' => $id,
                'user_id' => $user->id_user,
                'api_url' => $apiUrl
            ]);

            $errorMessage = 'Tidak dapat terhubung ke server prediksi. Silakan periksa koneksi internet Anda dan coba lagi.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 503);
            }
            return redirect()->route('prediksi.show', $id)->with('error', $errorMessage);
        } catch (RequestException $e) {
            // Handle request exceptions (timeout, SSL issues, etc.)
            Log::warning('Prediksi request error: ' . $e->getMessage(), [
                'mahasiswa_id' => $id,
                'user_id' => $user->id_user,
                'api_url' => $apiUrl
            ]);

            $errorMessage = 'Gagal mengirim permintaan ke server prediksi. Silakan coba lagi dalam beberapa saat.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 503);
            }
            return redirect()->route('prediksi.show', $id)->with('error', $errorMessage);
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            Log::error('Prediksi unexpected error: ' . $e->getMessage(), [
                'mahasiswa_id' => $id,
                'user_id' => $user->id_user,
                'api_url' => $apiUrl,
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 500);
            }
            return redirect()->route('prediksi.show', $id)->with('error', $errorMessage);
        }
    }

    /**
     * Get user-friendly error message based on HTTP status code
     */
    private function getHttpErrorMessage($statusCode)
    {
        switch ($statusCode) {
            case 400:
                return 'Data yang dikirim tidak valid. Silakan periksa kembali data riwayat akademik mahasiswa.';
            case 401:
                return 'Akses ditolak. Silakan login kembali untuk melanjutkan.';
            case 403:
                return 'Anda tidak memiliki izin untuk melakukan prediksi. Silakan hubungi administrator.';
            case 404:
                return 'Layanan prediksi tidak ditemukan. Silakan hubungi administrator untuk bantuan.';
            case 422:
                return 'Data yang dikirim tidak sesuai format yang diharapkan. Silakan periksa kembali data riwayat akademik.';
            case 429:
                return 'Terlalu banyak permintaan prediksi. Silakan tunggu beberapa saat sebelum mencoba lagi.';
            case 500:
                return 'Server prediksi sedang mengalami gangguan. Silakan coba lagi dalam beberapa saat atau hubungi administrator.';
            case 502:
                return 'Server prediksi sedang tidak tersedia. Silakan coba lagi nanti atau hubungi administrator.';
            case 503:
                return 'Layanan prediksi sedang dalam pemeliharaan. Silakan coba lagi dalam beberapa saat.';
            case 504:
                return 'Server prediksi tidak merespons. Silakan coba lagi dalam beberapa saat atau hubungi administrator.';
            default:
                return 'Gagal memproses prediksi. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.';
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
