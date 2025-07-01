<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_user' => 21,
                'id_angkatan' => 4,
                'no_hp_orang_tua' => '081234567893',
                'email_ortu' => 'ortu4@example.com',
                'status_prediksi_kelulusan' => 'tidak tepat waktu',
            ],
            [
                'id_user' => 22,
                'id_angkatan' => 5,
                'no_hp_orang_tua' => '081234567894',
                'email_ortu' => 'ortu5@example.com',
                'status_prediksi_kelulusan' => 'tidak tepat waktu',
            ]
        ];

        foreach ($data as $item) {
            Mahasiswa::create($item);
        }
    }
}
