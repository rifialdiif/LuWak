<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $title = 'Profile';
        return view('profile.profile', compact('title'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            'new_password_confirmation' => ['required', 'string'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        $user = Auth::user();

        // Verifikasi password saat ini
        if (!Hash::check($request->current_password, $user->password_hash)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        // Update password
        try {
            DB::table('user')
                ->where('id_user', $user->id_user)
                ->update(['password_hash' => Hash::make($request->new_password)]);

            return redirect()->back()
                ->with('success', 'Password berhasil diubah!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah password: ' . $e->getMessage())
                ->withInput();
        }
    }
}
