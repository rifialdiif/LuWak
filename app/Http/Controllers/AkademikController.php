<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Dpa;
use Illuminate\Support\Facades\Auth;

class AkademikController extends Controller
{


    public function index()
    {
        $user = Auth::user();
        $title = 'Riwayat Akademik Mahasiswa';

        // Query dasar untuk mahasiswa dengan relasi
        $query = Mahasiswa::with(['user', 'angkatan', 'user.prodi', 'riwayatAkademik']);

        // Filter berdasarkan role
        if ($user->role === 'admin') {
            // Admin dapat melihat semua mahasiswa
            $mahasiswas = $query->get();
        } elseif ($user->role === 'kaprodi') {
            // Kaprodi hanya dapat melihat mahasiswa dari prodi yang sama
            $mahasiswas = $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            })->get();
        } elseif ($user->role === 'dosen') {
            // Dosen hanya dapat melihat mahasiswa yang dibimbingnya
            $mahasiswas = $query->whereHas('dpa', function ($q) use ($user) {
                $q->where('id_user_dosen_pembimbing', $user->id_user);
            })->get();
        } else {
            // Role lain tidak dapat melihat data mahasiswa
            $mahasiswas = collect();
        }

        return view('akademik.index', compact('mahasiswas', 'title'));
    }

    public function show($id)
    {
        $user = Auth::user();

        // Query dasar untuk mahasiswa dengan relasi
        $query = Mahasiswa::with(['user', 'angkatan', 'user.prodi', 'riwayatAkademik']);

        // Filter berdasarkan role
        if ($user->role === 'admin') {
            // Admin dapat melihat semua mahasiswa
            $mahasiswa = $query->findOrFail($id);
        } elseif ($user->role === 'kaprodi') {
            // Kaprodi hanya dapat melihat mahasiswa dari prodi yang sama
            $mahasiswa = $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            })->findOrFail($id);
        } elseif ($user->role === 'dosen') {
            // Dosen hanya dapat melihat mahasiswa yang dibimbingnya
            $mahasiswa = $query->whereHas('dpa', function ($q) use ($user) {
                $q->where('id_user_dosen_pembimbing', $user->id_user);
            })->findOrFail($id);
        } else {
            // Role lain tidak dapat melihat data mahasiswa
            abort(403, 'Unauthorized access');
        }

        return view('akademik.detail', compact('mahasiswa'));
    }
}
