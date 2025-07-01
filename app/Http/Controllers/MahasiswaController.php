<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Angkatan;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MahasiswaController extends Controller
{
    public function index()
    {
        try {
            $mahasiswas = Mahasiswa::with(['user', 'angkatan'])
                ->whereHas('user', function ($query) {
                    $query->where('role', 'mahasiswa');
                })
                ->get();
            $title = 'Mahasiswa';
            $users = User::where('role', 'mahasiswa')->get();
            $angkatans = Angkatan::all();
            return view('mhs.index', compact('mahasiswas', 'title', 'users', 'angkatans'));
        } catch (\Exception $e) {
            Log::error($e);
            return back()->with('error', 'Gagal mengambil data mahasiswa.');
        }
    }
}
