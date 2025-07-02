<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dpa;

class DpaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_mahasiswa' => 3,
                'id_user_dosen_pembimbing' => 17,
            ],
            [
                'id_mahasiswa' => 7,
                'id_user_dosen_pembimbing' => 23,
            ],
        ];

        foreach ($data as $item) {
            Dpa::create($item);
        }
    }
}
