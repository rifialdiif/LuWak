<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\IntervensiOrtuMail;

class NotifikasiController extends Controller
{
    public function kirimIntervensi(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'metode' => 'required|in:email,sms',
            'nomor_email' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->metode === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('Format email tidak valid.');
                    }
                    if ($request->metode === 'sms' && !preg_match('/^\+?\d{10,15}$/', $value)) {
                        $fail('Format nomor WhatsApp tidak valid.');
                    }
                }
            ],
            'pesan' => 'required|string',
        ], [
            'nomor_email.required' => 'Email atau nomor WhatsApp wajib diisi.',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($request->id_mahasiswa);
        $statusKirim = 0;
        $errorMsg = null;

        try {
            if ($request->metode === 'email') {
                // Kirim email
                Mail::to($request->nomor_email)
                    ->send(new IntervensiOrtuMail(
                        $request->pesan,
                        $mahasiswa->user->nama,
                        $mahasiswa->user->nip_nim,
                        $mahasiswa->user->prodi->nama_prodi ?? ''
                    ));
                $statusKirim = 1;
            } elseif ($request->metode === 'sms') {
                // Simpan ke tabel notifikasi
                Notifikasi::create([
                    'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                    'id_user' => Auth::id(),
                    'jenis_kirim' => 'whatsapp',
                    'waktu_kirim' => now(),
                    'isi_pesan' => $request->pesan,
                    'status_kirim' => 1,
                ]);
                // Siapkan nomor dan pesan untuk WhatsApp
                $nomor = preg_replace('/[^0-9]/', '', $request->nomor_email);
                if (substr($nomor, 0, 1) === '0') {
                    $nomor = '62' . substr($nomor, 1);
                }
                $pesan = $request->pesan;
                // Redirect ke prediksi.show dengan nomor dan pesan WhatsApp di session
                return redirect()->route('prediksi.show', $mahasiswa->id_mahasiswa)
                    ->with([
                        'success' => 'Notifikasi intervensi berhasil dikirim.',
                        'wa_nomor' => $nomor,
                        'wa_pesan' => $pesan,
                    ]);
            }
        } catch (\Exception $e) {
            $statusKirim = 0;
            $errorMsg = $e->getMessage();
        }

        // Simpan ke tabel notifikasi untuk email
        if ($request->metode === 'email') {
            Notifikasi::create([
                'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                'id_user' => Auth::id(),
                'jenis_kirim' => 'email',
                'waktu_kirim' => now(),
                'isi_pesan' => $request->pesan,
                'status_kirim' => $statusKirim,
            ]);
        }

        if ($statusKirim) {
            return redirect()->route('prediksi.show', $mahasiswa->id_mahasiswa)
                ->with('success', 'Notifikasi intervensi berhasil dikirim.');
        } else {
            return redirect()->route('prediksi.show', $mahasiswa->id_mahasiswa)
                ->with('error', 'Notifikasi gagal dikirim. ' . ($errorMsg ?? ''));
        }
    }
}
