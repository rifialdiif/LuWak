<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dpa;
use App\Models\Mahasiswa;
use App\Models\Angkatan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DpaController extends Controller
{

    public function index()
    {
        $title = 'Dosen Pembimbing Akademik (DPA)';
        $dpas = Dpa::with([
            'dosenPembimbing.prodi',
            'mahasiswa.user',
            'mahasiswa.angkatan'
        ])->get();

        // Group by dosen
        $grouped = $dpas->groupBy('id_user_dosen_pembimbing');
        $data = $grouped->map(function ($group) {
            $first = $group->first();
            return [
                'id_dosen' => $first->dosenPembimbing->id_user ?? null,
                'nama_dpa' => $first->dosenPembimbing->nama ?? '-',
                'prodi' => $first->dosenPembimbing->prodi->nama_prodi ?? '-',
                'jumlah_mahasiswa' => $group->count(),
                'mahasiswas' => $group->map(function ($dpa) {
                    return [
                        'id_dpa' => $dpa->id,
                        'nama' => $dpa->mahasiswa->user->nama ?? '-',
                        'nim' => $dpa->mahasiswa->user->nip_nim ?? '-',
                        'angkatan' => $dpa->mahasiswa->angkatan->tahun_angkatan ?? '-',
                        'id_prodi' => $dpa->mahasiswa->user->id_prodi ?? null,
                    ];
                })->toArray(),
            ];
        })->values();

        $dosens = User::where('role', 'dpa')->get();
        $dosenProdiIds = $dosens->pluck('id_prodi')->unique()->toArray();
        $mahasiswas = Mahasiswa::with(['user', 'angkatan'])
            ->withoutDpa()
            ->whereHas('user', function ($q) use ($dosenProdiIds) {
                $q->whereIn('id_prodi', $dosenProdiIds);
            })
            ->get();
        $angkatans = Angkatan::all();

        return view('dpa.index', compact('data', 'title', 'mahasiswas', 'angkatans', 'dosens'));
    }

    public function getMahasiswaByDosen($dosenId)
    {
        $dosen = User::findOrFail($dosenId);
        $mahasiswas = Mahasiswa::with(['user'])
            ->withoutDpa()
            ->whereHas('user', function ($q) use ($dosen) {
                $q->where('id_prodi', $dosen->id_prodi);
            })
            ->get();

        $result = $mahasiswas->map(function ($mhs) {
            return [
                'id' => $mhs->id_mahasiswa,
                'nama' => $mhs->user->nama ?? '-',
                'nip_nim' => $mhs->user->nip_nim ?? '-',
            ];
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $messages = [
            'id_user_dosen_pembimbing_mahasiswa.required' => 'Dosen pembimbing harus dipilih pada tab Mahasiswa.',
            'id_user_dosen_pembimbing_mahasiswa.exists' => 'Dosen pembimbing tidak valid.',
            'id_mahasiswa.required' => 'Minimal satu mahasiswa harus dipilih.',
            'id_mahasiswa.array' => 'Format mahasiswa tidak valid.',
            'id_mahasiswa.*.exists' => 'Mahasiswa yang dipilih tidak valid.',
            'id_user_dosen_pembimbing_angkatan.required' => 'Dosen pembimbing harus dipilih pada tab Angkatan.',
            'id_user_dosen_pembimbing_angkatan.exists' => 'Dosen pembimbing tidak valid.',
            'id_angkatan.required' => 'Angkatan harus dipilih.',
            'id_angkatan.exists' => 'Angkatan tidak valid.',
        ];
        $request->validate([
            'id_user_dosen_pembimbing_mahasiswa' => 'nullable|exists:user,id_user',
            'id_mahasiswa' => 'nullable|array',
            'id_mahasiswa.*' => 'exists:mahasiswa,id_mahasiswa',
            'id_user_dosen_pembimbing_angkatan' => 'nullable|exists:user,id_user',
            'id_angkatan' => 'nullable|exists:angkatan,id_angkatan',
        ], $messages);

        DB::beginTransaction();
        try {
            // Berdasarkan Mahasiswa (multi)
            if ($request->filled('id_user_dosen_pembimbing_mahasiswa') && $request->filled('id_mahasiswa')) {
                $dosenId = $request->id_user_dosen_pembimbing_mahasiswa;
                foreach ($request->id_mahasiswa as $mhsId) {
                    Dpa::create([
                        'id_mahasiswa' => $mhsId,
                        'id_user_dosen_pembimbing' => $dosenId,
                    ]);
                }
                DB::commit();
                return redirect()->route('dpa.index')->with('success', 'DPA berhasil ditambahkan berdasarkan mahasiswa.');
            }
            // Berdasarkan Angkatan
            elseif ($request->filled('id_user_dosen_pembimbing_angkatan') && $request->filled('id_angkatan')) {
                $dosenId = $request->id_user_dosen_pembimbing_angkatan;
                $angkatanId = $request->id_angkatan;
                $mahasiswas = Mahasiswa::where('id_angkatan', $angkatanId)
                    ->withoutDpa()
                    ->get();
                foreach ($mahasiswas as $mhs) {
                    Dpa::create([
                        'id_mahasiswa' => $mhs->id_mahasiswa,
                        'id_user_dosen_pembimbing' => $dosenId,
                    ]);
                }
                DB::commit();
                return redirect()->route('dpa.index')->with('success', 'DPA berhasil ditambahkan berdasarkan angkatan.');
            }
            // Tidak valid
            else {
                return redirect()->route('dpa.index')->with('error', 'Data tidak valid.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menambah DPA: ' . $e->getMessage());
            return redirect()->route('dpa.index')->with('error', 'Terjadi kesalahan saat menambah DPA.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user_dosen_pembimbing' => 'required|exists:user,id_user',
        ], [
            'id_user_dosen_pembimbing.required' => 'Dosen pembimbing harus dipilih.',
            'id_user_dosen_pembimbing.exists' => 'Dosen pembimbing tidak valid.',
        ]);

        DB::beginTransaction();
        try {
            $dpa = Dpa::findOrFail($id);
            $dpa->id_user_dosen_pembimbing = $request->id_user_dosen_pembimbing;
            $dpa->save();
            DB::commit();
            return redirect()->route('dpa.index')->with('success', 'Dosen pembimbing berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update DPA: ' . $e->getMessage());
            return redirect()->route('dpa.index')->with('error', 'Terjadi kesalahan saat mengubah dosen pembimbing.');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $dpa = Dpa::findOrFail($id);
            $dpa->delete();
            DB::commit();
            return redirect()->route('dpa.index')->with('success', 'Mahasiswa berhasil dihapus dari daftar DPA.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus DPA: ' . $e->getMessage());
            return redirect()->route('dpa.index')->with('error', 'Terjadi kesalahan saat menghapus relasi DPA.');
        }
    }
}
