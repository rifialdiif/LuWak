<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\RiwayatAkademik;
use App\Models\User;
use App\Models\Dpa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        } elseif ($user->role === 'DPA') {
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

        // Jika mahasiswa, paksa hanya bisa lihat datanya sendiri
        if ($user->role === 'mahasiswa') {
            $id = $user->mahasiswa->id_mahasiswa ?? null;
            if (!$id) {
                abort(403, 'Mahasiswa tidak valid.');
            }
        }

        // Query dasar untuk mahasiswa dengan relasi
        $query = Mahasiswa::with(['user', 'angkatan', 'user.prodi', 'riwayatAkademik']);

        // Filter berdasarkan role
        if ($user->role === 'admin') {
            $mahasiswa = $query->findOrFail($id);
        } elseif ($user->role === 'kaprodi') {
            $mahasiswa = $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            })->findOrFail($id);
        } elseif ($user->role === 'DPA') {
            $mahasiswa = $query->whereHas('dpa', function ($q) use ($user) {
                $q->where('id_user_dosen_pembimbing', $user->id_user);
            })->findOrFail($id);
        } elseif ($user->role === 'mahasiswa') {
            $mahasiswa = $query->findOrFail($id);
        } else {
            abort(403, 'Unauthorized access');
        }

        // Ambil enum status semester
        $type = DB::select("SHOW COLUMNS FROM riwayat_akademik WHERE Field = 'status_semester_1'")[0]->Type;
        preg_match('/enum\((.*)\)/', $type, $matches);
        $enumStr = $matches[1];
        $statusSemesterOptions = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $enumStr));

        // Ambil prediksi terakhir mahasiswa
        $prediksiTerakhir = \App\Models\Prediksi::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->orderByDesc('tanggal_prediksi')
            ->first();

        return view('akademik.detail', compact('mahasiswa', 'statusSemesterOptions', 'prediksiTerakhir'));
    }

    public function store(Request $request)
    {
        $messages = [
            'id_mahasiswa.required' => 'Mahasiswa tidak ditemukan.',
            'id_mahasiswa.exists' => 'Mahasiswa tidak valid.',
            'ips_semester_1.required' => 'Nilai IPS semester 1 wajib diisi.',
            'ips_semester_2.required' => 'Nilai IPS semester 2 wajib diisi.',
            'ips_semester_3.required' => 'Nilai IPS semester 3 wajib diisi.',
            'ips_semester_4.required' => 'Nilai IPS semester 4 wajib diisi.',
            'ips_semester_1.numeric' => 'Nilai IPS semester 1 harus berupa angka.',
            'ips_semester_2.numeric' => 'Nilai IPS semester 2 harus berupa angka.',
            'ips_semester_3.numeric' => 'Nilai IPS semester 3 harus berupa angka.',
            'ips_semester_4.numeric' => 'Nilai IPS semester 4 harus berupa angka.',
            'status_semester_1.required' => 'Status semester 1 wajib dipilih.',
            'status_semester_2.required' => 'Status semester 2 wajib dipilih.',
            'status_semester_3.required' => 'Status semester 3 wajib dipilih.',
            'status_semester_4.required' => 'Status semester 4 wajib dipilih.',
            'sks_lulus.required' => 'Total SKS lulus wajib diisi.',
            'sks_tidak_lulus.required' => 'Total SKS tidak lulus wajib diisi.',
            'dokumen_pendukung.required' => 'File transkrip wajib diupload.',
            'dokumen_pendukung.mimes' => 'File transkrip harus berupa PDF, JPG, JPEG, atau PNG.',
            'dokumen_pendukung.max' => 'Ukuran file transkrip maksimal 5MB.',
        ];

        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'ips_semester_1' => 'required|numeric|between:0,4.00',
            'ips_semester_2' => 'required|numeric|between:0,4.00',
            'ips_semester_3' => 'required|numeric|between:0,4.00',
            'ips_semester_4' => 'required|numeric|between:0,4.00',
            'status_semester_1' => 'required|string',
            'status_semester_2' => 'required|string',
            'status_semester_3' => 'required|string',
            'status_semester_4' => 'required|string',
            'sks_lulus' => 'required|integer|min:0',
            'sks_tidak_lulus' => 'required|integer|min:0',
            'dokumen_pendukung' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], $messages);

        try {
            $fileName = null;
            $riwayat = RiwayatAkademik::where('id_mahasiswa', $request->id_mahasiswa)->first();
            if ($request->hasFile('dokumen_pendukung')) {
                // Hapus file lama jika ada dan update
                if ($riwayat && $riwayat->dokumen_pendukung && Storage::disk('public')->exists('file_pendukung/' . $riwayat->dokumen_pendukung)) {
                    Storage::disk('public')->delete('file_pendukung/' . $riwayat->dokumen_pendukung);
                }
                $path = $request->file('dokumen_pendukung')->store('file_pendukung', 'public');
                $fileName = basename($path);
            } else if ($riwayat) {
                $fileName = $riwayat->dokumen_pendukung;
            }

            // Tentukan status_validasi sesuai role
            $statusValidasi = Auth::user()->role === 'mahasiswa' ? 'pending' : 'valid';
            $additionalValidasi = [];
            if (Auth::user()->role !== 'mahasiswa') {
                $additionalValidasi['validasi_by'] = Auth::user()->id_user;
                $additionalValidasi['validasi_at'] = now();
            }

            RiwayatAkademik::updateOrCreate(
                ['id_mahasiswa' => $request->id_mahasiswa],
                array_merge([
                    'ips_semester_1' => $request->ips_semester_1,
                    'ips_semester_2' => $request->ips_semester_2,
                    'ips_semester_3' => $request->ips_semester_3,
                    'ips_semester_4' => $request->ips_semester_4,
                    'status_semester_1' => $request->status_semester_1,
                    'status_semester_2' => $request->status_semester_2,
                    'status_semester_3' => $request->status_semester_3,
                    'status_semester_4' => $request->status_semester_4,
                    'total_sks_ditempuh' => $request->sks_lulus,
                    'total_sks_tidak_lulus' => $request->sks_tidak_lulus,
                    'dokumen_pendukung' => $fileName,
                    'status_validasi' => $statusValidasi,
                ], $additionalValidasi)
            );

            return redirect()->route('akademik.show', $request->id_mahasiswa)->with('success', 'Data riwayat akademik berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan riwayat akademik: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi atau hubungi admin.');
        }
    }

    public function update(Request $request, $id_mahasiswa)
    {
        $messages = [
            'id_mahasiswa.required' => 'Mahasiswa tidak ditemukan.',
            'id_mahasiswa.exists' => 'Mahasiswa tidak valid.',
            'ips_semester_1.required' => 'Nilai IPS semester 1 wajib diisi.',
            'ips_semester_2.required' => 'Nilai IPS semester 2 wajib diisi.',
            'ips_semester_3.required' => 'Nilai IPS semester 3 wajib diisi.',
            'ips_semester_4.required' => 'Nilai IPS semester 4 wajib diisi.',
            'ips_semester_1.numeric' => 'Nilai IPS semester 1 harus berupa angka.',
            'ips_semester_2.numeric' => 'Nilai IPS semester 2 harus berupa angka.',
            'ips_semester_3.numeric' => 'Nilai IPS semester 3 harus berupa angka.',
            'ips_semester_4.numeric' => 'Nilai IPS semester 4 harus berupa angka.',
            'status_semester_1.required' => 'Status semester 1 wajib dipilih.',
            'status_semester_2.required' => 'Status semester 2 wajib dipilih.',
            'status_semester_3.required' => 'Status semester 3 wajib dipilih.',
            'status_semester_4.required' => 'Status semester 4 wajib dipilih.',
            'sks_lulus.required' => 'Total SKS lulus wajib diisi.',
            'sks_tidak_lulus.required' => 'Total SKS tidak lulus wajib diisi.',
            'dokumen_pendukung.mimes' => 'File transkrip harus berupa PDF, JPG, JPEG, atau PNG.',
            'dokumen_pendukung.max' => 'Ukuran file transkrip maksimal 5MB.',
        ];

        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'ips_semester_1' => 'required|numeric|between:0,4.00',
            'ips_semester_2' => 'required|numeric|between:0,4.00',
            'ips_semester_3' => 'required|numeric|between:0,4.00',
            'ips_semester_4' => 'required|numeric|between:0,4.00',
            'status_semester_1' => 'required|string',
            'status_semester_2' => 'required|string',
            'status_semester_3' => 'required|string',
            'status_semester_4' => 'required|string',
            'sks_lulus' => 'required|integer|min:0',
            'sks_tidak_lulus' => 'required|integer|min:0',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], $messages);

        try {
            $fileName = null;
            $riwayat = RiwayatAkademik::where('id_mahasiswa', $request->id_mahasiswa)->first();
            $updateStatusValidasi = false;
            if ($request->hasFile('dokumen_pendukung')) {
                // Hapus file lama jika ada dan update
                if ($riwayat && $riwayat->dokumen_pendukung && Storage::disk('public')->exists('file_pendukung/' . $riwayat->dokumen_pendukung)) {
                    Storage::disk('public')->delete('file_pendukung/' . $riwayat->dokumen_pendukung);
                }
                $path = $request->file('dokumen_pendukung')->store('file_pendukung', 'public');
                $fileName = basename($path);
                // Upload file baru, status_validasi tergantung role
                $statusValidasi = Auth::user()->role === 'mahasiswa' ? 'pending' : 'valid';
                $updateStatusValidasi = true;
            } else if ($riwayat) {
                $fileName = $riwayat->dokumen_pendukung;
            }

            $data = [
                'ips_semester_1' => $request->ips_semester_1,
                'ips_semester_2' => $request->ips_semester_2,
                'ips_semester_3' => $request->ips_semester_3,
                'ips_semester_4' => $request->ips_semester_4,
                'status_semester_1' => $request->status_semester_1,
                'status_semester_2' => $request->status_semester_2,
                'status_semester_3' => $request->status_semester_3,
                'status_semester_4' => $request->status_semester_4,
                'total_sks_ditempuh' => $request->sks_lulus,
                'total_sks_tidak_lulus' => $request->sks_tidak_lulus,
                'dokumen_pendukung' => $fileName,
            ];
            if ($updateStatusValidasi) {
                $data['status_validasi'] = $statusValidasi;
            }
            if (Auth::user()->role !== 'mahasiswa') {
                $data['validasi_by'] = Auth::user()->id_user;
                $data['validasi_at'] = now();
            }

            RiwayatAkademik::updateOrCreate(
                ['id_mahasiswa' => $request->id_mahasiswa],
                $data
            );

            return redirect()->route('akademik.show', $request->id_mahasiswa)->with('success', 'Data riwayat akademik berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal update riwayat akademik: ' . $e->getMessage());
            session()->flash('edit_modal', true);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat update data. Silakan coba lagi atau hubungi admin.');
        }
    }

    public function destroy($id_mahasiswa)
    {
        try {
            $riwayat = RiwayatAkademik::where('id_mahasiswa', $id_mahasiswa)->first();
            if (!$riwayat) {
                return back()->with('error', 'Data riwayat akademik tidak ditemukan.');
            }
            // Hapus file transkrip jika ada
            if ($riwayat->dokumen_pendukung && Storage::disk('public')->exists('file_pendukung/' . $riwayat->dokumen_pendukung)) {
                Storage::disk('public')->delete('file_pendukung/' . $riwayat->dokumen_pendukung);
            }
            $riwayat->delete();
            return back()->with('success', 'Data riwayat akademik berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus riwayat akademik: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi atau hubungi admin.');
        }
    }

    public function validasiTranskrip(Request $request, $id_mahasiswa)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'kaprodi', 'DPA'])) {
            abort(403, 'Anda tidak berhak melakukan validasi.');
        }
        $request->validate([
            'aksi' => 'required|in:valid,tidak_valid',
            'catatan_validasi' => 'required_if:aksi,tidak_valid',
        ]);
        try {
            $riwayat = RiwayatAkademik::where('id_mahasiswa', $id_mahasiswa)->first();
            if (!$riwayat) {
                return back()->with('error', 'Data riwayat akademik tidak ditemukan.');
            }
            $riwayat->status_validasi = $request->aksi === 'valid' ? 'valid' : 'tidak valid';
            $riwayat->validasi_by = $user->id_user;
            $riwayat->validasi_at = now();
            if ($request->aksi === 'tidak_valid') {
                $riwayat->catatan_validasi = $request->catatan_validasi;
            } else {
                $riwayat->catatan_validasi = null;
            }
            $riwayat->save();
            return redirect()->route('akademik.show', $id_mahasiswa)->with('success', 'Status validasi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal validasi transkrip: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat validasi. Silakan coba lagi atau hubungi admin.');
        }
    }
}
