<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatAkademik;
use App\Models\Mahasiswa;

class RiwayatAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua mahasiswa yang ada
        $mahasiswas = Mahasiswa::all();

        // Jika tidak ada mahasiswa, buat data dummy
        if ($mahasiswas->isEmpty()) {
            $data = [
                [
                    'id_mahasiswa' => 1,
                    'ips_semester_1' => 3.25,
                    'ips_semester_2' => 3.45,
                    'ips_semester_3' => 3.30,
                    'ips_semester_4' => 3.60,
                    'status_semester_1' => 'lulus',
                    'status_semester_2' => 'lulus',
                    'status_semester_3' => 'lulus',
                    'status_semester_4' => 'lulus',
                    'total_sks_lulus' => 72,
                    'total_sks_tidak_lulus' => 0,
                    'dokumen_transkrip' => '',
                    'status_validasi' => 'valid',
                ],
                [
                    'id_mahasiswa' => 2,
                    'ips_semester_1' => 2.85,
                    'ips_semester_2' => 3.10,
                    'ips_semester_3' => 2.95,
                    'ips_semester_4' => 3.20,
                    'status_semester_1' => 'lulus',
                    'status_semester_2' => 'lulus',
                    'status_semester_3' => 'lulus',
                    'status_semester_4' => 'lulus',
                    'total_sks_lulus' => 68,
                    'total_sks_tidak_lulus' => 4,
                    'dokumen_transkrip' => '',
                    'status_validasi' => '',
                ],
                [
                    'id_mahasiswa' => 3,
                    'ips_semester_1' => 3.50,
                    'ips_semester_2' => 3.75,
                    'ips_semester_3' => 3.80,
                    'ips_semester_4' => 3.90,
                    'status_semester_1' => 'lulus',
                    'status_semester_2' => 'lulus',
                    'status_semester_3' => 'lulus',
                    'status_semester_4' => 'lulus',
                    'total_sks_lulus' => 76,
                    'total_sks_tidak_lulus' => 0,
                    'dokumen_transkrip' => '',
                    'status_validasi' => '',
                ],
                [
                    'id_mahasiswa' => 4,
                    'ips_semester_1' => 2.50,
                    'ips_semester_2' => 2.75,
                    'ips_semester_3' => 2.60,
                    'ips_semester_4' => 2.90,
                    'status_semester_1' => 'lulus',
                    'status_semester_2' => 'lulus',
                    'status_semester_3' => 'lulus',
                    'status_semester_4' => 'lulus',
                    'total_sks_lulus' => 64,
                    'total_sks_tidak_lulus' => 8,
                    'dokumen_transkrip' => '',
                    'status_validasi' => '',
                ],
                [
                    'id_mahasiswa' => 5,
                    'ips_semester_1' => 3.00,
                    'ips_semester_2' => 3.15,
                    'ips_semester_3' => 3.25,
                    'ips_semester_4' => 3.35,
                    'status_semester_1' => 'lulus',
                    'status_semester_2' => 'lulus',
                    'status_semester_3' => 'lulus',
                    'status_semester_4' => 'lulus',
                    'total_sks_lulus' => 70,
                    'total_sks_tidak_lulus' => 2,
                    'dokumen_transkrip' => '',
                    'status_validasi' => '',
                ]
            ];
        } else {
            // Buat data berdasarkan mahasiswa yang ada
            $data = [];
            foreach ($mahasiswas as $index => $mahasiswa) {
                $ips1 = rand(250, 400) / 100; // 2.50 - 4.00
                $ips2 = rand(250, 400) / 100;
                $ips3 = rand(250, 400) / 100;
                $ips4 = rand(250, 400) / 100;

                $totalSksLulus = rand(60, 80);
                $totalSksTidakLulus = rand(0, 10);

                $statuses = ['lulus', 'lulus', 'lulus', 'lulus']; // Kebanyakan lulus
                $validasiStatuses = ['valid', 'valid', 'pending', 'invalid'];

                $data[] = [
                    'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                    'ips_semester_1' => round($ips1, 2),
                    'ips_semester_2' => round($ips2, 2),
                    'ips_semester_3' => round($ips3, 2),
                    'ips_semester_4' => round($ips4, 2),
                    'status_semester_1' => $statuses[0],
                    'status_semester_2' => $statuses[1],
                    'status_semester_3' => $statuses[2],
                    'status_semester_4' => $statuses[3],
                    'total_sks_lulus' => $totalSksLulus,
                    'total_sks_tidak_lulus' => $totalSksTidakLulus,
                    'dokumen_transkrip' => '',
                    'status_validasi' => '',
                ];
            }
        }

        foreach ($data as $item) {
            RiwayatAkademik::create($item);
        }
    }
}
