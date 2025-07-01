<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class AngkatanController extends Controller
{

    public function index()
    {
        try {
            $title = 'Angkatan';
            $angkatans = Angkatan::orderBy('tahun_angkatan', 'desc')->get();
            return view('angkatan.index', compact('angkatans', 'title'));
        } catch (\Exception $e) {
            Log::error($e);
            return back()->with('error', 'Gagal mengambil data angkatan.');
        }
    }

    public function create()
    {
        return view('angkatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_angkatan' => 'required|integer|unique:angkatan,tahun_angkatan',
        ], [
            'tahun_angkatan.required' => 'Tahun angkatan wajib diisi.',
            'tahun_angkatan.integer' => 'Tahun angkatan harus berupa angka.',
            'tahun_angkatan.unique' => 'Tahun angkatan sudah terdaftar.',
        ]);

        try {
            Angkatan::create($validated);
            return redirect()->route('angkatan.index')->with('success', 'Angkatan berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menambah angkatan.']);
        }
    }


    public function update(Request $request, $id)
    {
        $messages = [
            'tahun_angkatan.required' => 'Tahun angkatan wajib diisi.',
            'tahun_angkatan.digits' => 'Tahun angkatan harus terdiri dari 4 digit angka.',
            'tahun_angkatan.unique' => 'Tahun angkatan sudah terdaftar.',
        ];

        $request->validate([
            'tahun_angkatan' => 'required|digits:4|unique:angkatan,tahun_angkatan,' . $id . ',id_angkatan',
        ], $messages);

        try {
            $angkatan = Angkatan::findOrFail($id);
            $angkatan->update($request->all());
            return redirect()->route('angkatan.index')->with('success', 'Data angkatan berhasil diupdate.');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->withErrors(['error' => 'Gagal update data angkatan: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $angkatan = Angkatan::findOrFail($id);
            $angkatan->delete();
            return redirect()->route('angkatan.index')->with('success', 'Data angkatan berhasil dihapus.');
        } catch (QueryException $e) {
            // Jika gagal karena constraint relasi
            return redirect()->route('angkatan.index')->with('error', 'Data angkatan tidak dapat dihapus karena masih digunakan.');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route('angkatan.index')->with('error', 'Gagal menghapus data angkatan.');
        }
    }
}
