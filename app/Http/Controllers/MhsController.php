<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Angkatan;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class MhsController extends Controller
{
    //

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:user,id_user',
            'id_angkatan' => 'required|exists:angkatan,id_angkatan',
            'no_hp_orang_tua' => 'required|string|max:15',
            'email_ortu' => 'required|email|max:100',
        ], [
            'id_user.required' => 'Mahasiswa wajib dipilih.',
            'id_angkatan.required' => 'Angkatan wajib dipilih.',
            'no_hp_orang_tua.required' => 'No HP Orang Tua wajib diisi.',
            'email_ortu.required' => 'E-mail Orang Tua wajib diisi.',
        ]);

        try {
            Mahasiswa::create($validated);
            return redirect()->route('mhs.index')->with('success', 'Mahasiswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menambah mahasiswa.']);
        }
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $validated = $request->validate([
            'id_user' => 'required|exists:user,id_user',
            'id_angkatan' => 'required|exists:angkatan,id_angkatan',
            'no_hp_orang_tua' => 'required|string|max:15',
            'email_ortu' => 'required|email|max:100',
        ], [
            'id_user.required' => 'Mahasiswa wajib dipilih.',
            'id_angkatan.required' => 'Angkatan wajib dipilih.',
            'no_hp_orang_tua.required' => 'No HP Orang Tua wajib diisi.',
            'email_ortu.required' => 'E-mail Orang Tua wajib diisi.',
        ]);

        try {
            $mahasiswa->update($validated);
            return redirect()->route('mhs.index')->with('success', 'Mahasiswa berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui mahasiswa.']);
        }
    }

    public function destroy($id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $mahasiswa->delete();
            return redirect()->route('mhs.index')->with('success', 'Mahasiswa berhasil dihapus!');
        } catch (QueryException $e) {
            // error relasi foreign key
            Log::error('Gagal hapus mahasiswa (relasi): ' . $e->getMessage());
            return redirect()->route('mhs.index')->withErrors(['error' => 'Mahasiswa tidak dapat dihapus karena masih digunakan pada data lain.']);
        } catch (\Exception $e) {
            Log::error('Gagal hapus mahasiswa: ' . $e->getMessage());
            return redirect()->route('mhs.index')->withErrors(['error' => 'Terjadi kesalahan saat menghapus mahasiswa: ' . $e->getMessage()]);
        }
    }
}
