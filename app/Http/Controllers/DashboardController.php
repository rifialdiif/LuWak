<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Angkatan;
use App\Models\Prediksi;
use App\Models\Dpa;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswas = collect();
        $angkatanStats = [];
        $totalMahasiswa = 0;
        $tepatWaktu = 0;
        $berisiko = 0;
        $akurasiModel = 92; // Dummy, ganti dengan dinamis jika ada
        $chartData = [
            'angkatan' => [],
            'tepat_count' => [],
            'tidak_count' => [],
            'tepat_percent' => [],
            'tidak_percent' => [],
        ];

        // Query dasar mahasiswa (tambah eager loading riwayatAkademik)
        $query = Mahasiswa::with(['user.prodi', 'angkatan', 'prediksi', 'riwayatAkademik']);

        if ($user->role === 'admin') {
            $mahasiswas = $query->get();
        } elseif ($user->role === 'kaprodi') {
            $mahasiswas = $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            })->get();
        } elseif ($user->role === 'DPA') {
            $mahasiswas = $query->whereHas('dpa', function ($q) use ($user) {
                $q->where('id_user_dosen_pembimbing', $user->id_user);
            })->get();
        }

        // Statistik utama
        $totalMahasiswa = $mahasiswas->count();
        $tepatWaktu = 0;
        $berisiko = 0;
        foreach ($mahasiswas as $m) {
            $prediksi = $m->prediksi->sortByDesc('tanggal_prediksi')->first();
            if ($prediksi) {
                if ($prediksi->hasil_prediksi == 0) {
                    $tepatWaktu++;
                } elseif ($prediksi->hasil_prediksi == 1) {
                    $berisiko++;
                }
            }
        }
        $persenTepat = $totalMahasiswa > 0 ? round($tepatWaktu / $totalMahasiswa * 100) : 0;

        // Distribusi per angkatan
        $angkatanList = Angkatan::orderBy('tahun_angkatan')->get();
        foreach ($angkatanList as $angkatan) {
            $mhsAngkatan = $mahasiswas->where('id_angkatan', $angkatan->id_angkatan);
            $total = $mhsAngkatan->count();
            $tepat = 0;
            $tidak = 0;
            foreach ($mhsAngkatan as $m) {
                $prediksi = $m->prediksi->sortByDesc('tanggal_prediksi')->first();
                if ($prediksi && $prediksi->hasil_prediksi == 0) {
                    $tepat++;
                } elseif ($prediksi && $prediksi->hasil_prediksi == 1) {
                    $tidak++;
                }
            }
            $persen_tepat = $total > 0 ? round($tepat / $total * 100) : 0;
            $persen_tidak = $total > 0 ? round($tidak / $total * 100) : 0;
            $angkatanStats[] = [
                'tahun' => $angkatan->tahun_angkatan,
                'tepat' => $tepat,
                'total' => $total,
                'persen' => $persen_tepat,
            ];
            $chartData['angkatan'][] = $angkatan->tahun_angkatan;
            $chartData['tepat_count'][] = $tepat;
            $chartData['tidak_count'][] = $tidak;
            $chartData['tepat_percent'][] = $persen_tepat;
            $chartData['tidak_percent'][] = $persen_tidak;
        }

        // Pie chart data: total tepat waktu dan berisiko (dari hasil_prediksi terakhir)
        $pieTepat = 0;
        $pieBerisiko = 0;
        foreach ($mahasiswas as $m) {
            $prediksi = $m->prediksi->sortByDesc('tanggal_prediksi')->first();
            if ($prediksi) {
                if ($prediksi->hasil_prediksi == 0) {
                    $pieTepat++;
                } elseif ($prediksi->hasil_prediksi == 1) {
                    $pieBerisiko++;
                }
            }
        }
        $chartPie = [
            'labels' => ['Tepat Waktu', 'Berisiko Tidak Tepat Waktu'],
            'data' => [$pieTepat, $pieBerisiko],
        ];

        // Data untuk datatable
        $datatable = $mahasiswas->map(function ($m) {
            $prediksi = $m->prediksi->sortByDesc('tanggal_prediksi')->first();
            $sks_gagal = $m->riwayatAkademik->total_sks_tidak_lulus ?? 0;
            // Mapping prediksi
            $hasil_prediksi = null;
            $confidence = null;
            if ($prediksi) {
                if ($prediksi->hasil_prediksi == 0) {
                    $hasil_prediksi = 'Tepat Waktu';
                } elseif ($prediksi->hasil_prediksi == 1) {
                    $hasil_prediksi = 'Berisiko Tidak Tepat Waktu';
                } else {
                    $hasil_prediksi = '-';
                }
                $confidence = is_numeric($prediksi->confidence_score) ? round($prediksi->confidence_score * 100) : 0;
            } else {
                $hasil_prediksi = '-';
                $confidence = 0;
            }
            return [
                'nim' => $m->user->nip_nim ?? '-',
                'nama' => $m->user->nama ?? '-',
                'angkatan' => $m->angkatan->tahun_angkatan ?? '-',
                'prodi' => $m->user->prodi->nama_prodi ?? '-',
                'sks_gagal' => $sks_gagal,
                'prediksi' => $hasil_prediksi,
                'probabilitas' => $confidence,
                'email_ortu' => $m->email_ortu ?? '',
                'no_hp_orang_tua' => $m->no_hp_orang_tua ?? '',
                'id_mahasiswa' => $m->id_mahasiswa ?? '',
            ];
        });

        // Faktor risiko utama (dummy, bisa diganti dinamis)
        $faktorRisiko = [
            ['label' => 'Total Akumulasi SKS Tidak Lulus', 'level' => 'High Risk', 'color' => 'danger', 'bg' => '#fff1f1'],
            ['label' => 'Indeks Prestasi Semester 3', 'level' => 'High Risk', 'color' => 'danger', 'bg' => '#fff1f1'],
            ['label' => 'Indeks Prestasi Semester 4', 'level' => 'High Risk', 'color' => 'danger', 'bg' => '#fff1f1'],
            ['label' => 'Total SKS Ditempuh', 'level' => 'Medium Risk', 'color' => 'secondary text-clear', 'bg' => '#fff6e6'],
            ['label' => 'Indeks Prestasi Semester 1', 'level' => 'Medium Risk', 'color' => 'secondary text-clear', 'bg' => '#fff6e6'],
        ];

        // Jika mahasiswa, dashboard kosong
        if ($user->role === 'mahasiswa') {
            // Ambil data riwayat akademik dan status validasi
            $mahasiswa = $user->mahasiswa;
            $riwayat = $mahasiswa ? $mahasiswa->riwayatAkademik : null;
            $statusValidasi = $riwayat ? $riwayat->status_validasi : null;
            $catatanValidasi = $riwayat ? $riwayat->catatan_validasi : null;
            // Ambil 10 riwayat prediksi terakhir
            $riwayatPrediksi = $mahasiswa ? $mahasiswa->prediksi()->orderByDesc('tanggal_prediksi')->take(10)->get() : collect();
            // Ambil prediksi terakhir
            $prediksiTerakhir = $mahasiswa ? $mahasiswa->prediksi()->orderByDesc('tanggal_prediksi')->first() : null;
            // FAQ dan saran (dummy, bisa diubah)
            $faq = [
                ['q' => 'Bagaimana cara sistem memprediksi kelulusan saya?', 'a' => 'Sistem menggunakan algoritma Random Forest yang menganalisis data akademik Anda seperti IPK, jumlah SKS, mata kuliah yang tidak lulus, dan pola akademik untuk memberikan prediksi kelulusan tepat waktu.'],
                ['q' => 'Seberapa akurat prediksi yang diberikan?', 'a' => 'Berdasarkan pengujian dengan data historis mahasiswa TRPL PEI, sistem memiliki tingkat akurasi di atas 80%. Namun, prediksi ini bersifat estimasi dan dapat berubah seiring perkembangan akademik Anda.'],
                ['q' => 'Dokumen apa saja yang perlu saya upload?', 'a' => 'Anda perlu mengupload transkrip nilai terbaru, Kartu Hasil Studi (KHS) semester aktif, dan Kartu Rencana Studi (KRS). Pastikan dokumen dalam format PDF dan dapat dibaca dengan jelas.'],
                ['q' => 'Bagaimana cara memperbaiki dokumen tidak valid?', 'a' => 'Silakan upload ulang dokumen yang sesuai format dan pastikan data benar.'],
                ['q' => 'Bagaimana jika prediksi saya menunjukkan risiko tidak lulus tepat waktu?', 'a' => 'Segera konsultasi dengan dosen pembimbing akademik untuk membuat rencana perbaikan. Jangan lupa untuk melakukan refleksi diri dan mulai berbenah agar dapat memperbaiki performa akademik Anda.'],
                ['q' => 'Siapa yang bisa saya hubungi jika ada masalah?', 'a' => 'Silakan hubungi DPA atau admin prodi Anda.'],
            ];
            // Saran general
            $saranUtama = [
                'Pertahankan performa akademik secara konsisten setiap semester',
                'Segera selesaikan mata kuliah yang tertunda atau belum lulus',
                'Manfaatkan fasilitas kampus dan layanan akademik yang tersedia',
                'Jaga komunikasi aktif dengan dosen pembimbing dan bagian akademik',
            ];
            $tipsTambahan = [
                'Buat jadwal belajar yang teratur dan realistis',
                'Ikuti kelompok belajar atau diskusi untuk memperdalam materi',
                'Pantau perkembangan nilai dan status akademik secara berkala',
                'Jaga kesehatan fisik dan mental selama masa studi',
            ];
            $saran = 'Pastikan data riwayat akademik dan dokumen pendukung Anda sudah benar dan lengkap untuk hasil prediksi yang akurat.';
            return view('dashboard.dash', [
                'isMahasiswa' => true,
                'statusValidasi' => $statusValidasi,
                'catatanValidasi' => $catatanValidasi,
                'riwayatPrediksi' => $riwayatPrediksi,
                'prediksiTerakhir' => $prediksiTerakhir,
                'faq' => $faq,
                'saranUtama' => $saranUtama,
                'tipsTambahan' => $tipsTambahan,
                'saran' => $saran,
            ]);
        }

        return view('dashboard.dash', [
            'isMahasiswa' => false,
            'totalMahasiswa' => $totalMahasiswa,
            'tepatWaktu' => $tepatWaktu,
            'berisiko' => $berisiko,
            'persenTepat' => $persenTepat,
            'akurasiModel' => $akurasiModel,
            'datatable' => $datatable,
            'angkatanStats' => $angkatanStats,
            'faktorRisiko' => $faktorRisiko,
            'chartData' => $chartData,
            'chartPie' => $chartPie,
        ]);
    }
}
