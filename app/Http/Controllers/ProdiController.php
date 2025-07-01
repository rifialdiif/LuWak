<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProdiController extends Controller
{
    public function index()
    {
        $title = 'Prodi';
        $prodis = Prodi::all();
        return view('prodi.index', compact('title', 'prodis'));
    }

    // public function create()
    // {
    //     return view('prodi.create');
    // }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_prodi' => ['required', 'string', 'max:100', 'unique:prodi,nama_prodi'],
        ], [
            'nama_prodi.required' => 'Nama prodi wajib diisi.',
            'nama_prodi.string' => 'Nama prodi harus berupa teks.',
            'nama_prodi.max' => 'Nama prodi maksimal 100 karakter.',
            'nama_prodi.unique' => 'Nama prodi sudah terdaftar, silakan gunakan nama lain.',
        ]);

        try {
            Prodi::create($validatedData);
            return redirect()
                ->route('prodi.index')
                ->with('success', 'Prodi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menambah Prodi: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('prodi.edit', compact('prodi'));
    }

    public function update(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);
        $validatedData = $request->validate([
            'nama_prodi' => 'required|string|max:100|unique:prodi,nama_prodi,' . $id . ',id_prodi',
        ], [
            'nama_prodi.required' => 'Nama prodi wajib diisi.',
            'nama_prodi.string' => 'Nama prodi harus berupa teks.',
            'nama_prodi.max' => 'Nama prodi maksimal 100 karakter.',
            'nama_prodi.unique' => 'Nama prodi sudah terdaftar, silakan gunakan nama lain.',
        ]);

        try {
            $prodi->update($validatedData);
            return redirect()
                ->route('prodi.index')
                ->with('success', 'Prodi berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat update Prodi: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            Prodi::findOrFail($id)->delete();
            return redirect()->route('prodi.index')->with('success', 'Prodi berhasil dihapus!');
        } catch (QueryException $e) {
            // error relasi foreign key
            // Penanganan jika gagal karena constraint/relasi
            Log::error('Gagal hapus prodi (relasi): ' . $e->getMessage());
            return redirect()->route('prodi.index')->withErrors(['error' => 'Prodi tidak dapat dihapus karena masih digunakan pada data lain.']);
        } catch (\Exception $e) {
            Log::error('Gagal hapus prodi: ' . $e->getMessage());
            return redirect()->route('prodi.index')->withErrors(['error' => 'Terjadi kesalahan saat menghapus prodi: ' . $e->getMessage()]);
        }
    }
}
