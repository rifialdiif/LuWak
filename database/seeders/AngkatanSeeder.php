<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Angkatan;

class AngkatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = range(2018, 2024);
        foreach ($years as $year) {
            Angkatan::firstOrCreate([
                'tahun_angkatan' => $year
            ]);
        }
    }
}
