<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $title = 'User';

        // Query dasar
        $query = User::with('prodi');

        // Filter berdasarkan role jika ada
        if ($request->filled('role_filter') && $request->role_filter !== 'all') {
            $query->where('role', $request->role_filter);
        }

        // Filter berdasarkan prodi jika ada
        if ($request->filled('prodi_filter') && $request->prodi_filter !== 'all') {
            $query->where('id_prodi', $request->prodi_filter);
        }



        $users = $query->get();
        $prodis = Prodi::all();
        // Ambil enum role dari database
        $roles = $this->getEnumValues('user', 'role');

        return view('user.index', compact('title', 'users', 'prodis', 'roles'));
    }

    public function create()
    {
        $prodis = Prodi::all();
        $roles = $this->getEnumValues('user', 'role');
        return view('user.create', compact('prodis', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip_nim' => 'required|string|max:30|unique:user,nip_nim',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6',
            'role' => 'required',
            'id_prodi' => 'required|exists:prodi,id_prodi',
        ], [
            'nip_nim.required' => 'NIP/NIM wajib diisi.',
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'role.required' => 'Role wajib dipilih.',
            'id_prodi.required' => 'Prodi wajib dipilih.',
        ]);

        try {
            $validated['password_hash'] = bcrypt($validated['password']);
            unset($validated['password']);

            User::create($validated);

            return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menambah user: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menambah user. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $prodis = Prodi::all();
        $roles = $this->getEnumValues('user', 'role');
        return view('user.edit', compact('user', 'prodis', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'nip_nim' => 'required|string|max:30|unique:user,nip_nim,' . $id . ',id_user',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $id . ',id_user',
            'role' => 'required',
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'password' => 'nullable|string|min:8',
        ], [
            'nip_nim.required' => 'NIP/NIM wajib diisi.',
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'role.required' => 'Role wajib dipilih.',
            'id_prodi.required' => 'Prodi wajib dipilih.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($request->filled('password')) {
            $validated['password_hash'] = bcrypt($request->password);
        }

        try {
            $user->update($validated);
            return redirect()->route('user.index')->with('success', 'User berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Gagal update user: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat update user. Silakan coba lagi.']);
        }
    }


    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Penanganan jika gagal karena constraint/relasi
            Log::error('Gagal hapus user (relasi): ' . $e->getMessage());
            return redirect()->route('user.index')->withErrors(['error' => 'User tidak dapat dihapus karena masih berelasi dengan data lain.']);
        } catch (\Exception $e) {
            Log::error('Gagal hapus user: ' . $e->getMessage());
            return redirect()->route('user.index')->withErrors(['error' => 'Terjadi kesalahan saat menghapus user. Silakan coba lagi.']);
        }
    }


    private function getEnumValues($table, $column)
    {
        // Menggunakan DB facade untuk mengambil tipe kolom dari tabel
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'")[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = [];
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "' ");
            $enum[] = $v;
        }
        return $enum;
    }
}
