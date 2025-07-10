<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

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
        $mahasiswa = Mahasiswa::with([
            'user.prodi',
            'angkatan',
            'dpa.dosenPembimbing',
            'riwayatAkademik',
        ])->findOrFail($id);
        return view('prediksi.detail', compact('mahasiswa'));
    }

    public function formIntervensi($id)
    {
        return view('prediksi.form_intervensi');
    }
}
