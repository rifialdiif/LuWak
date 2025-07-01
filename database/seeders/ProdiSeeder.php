<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prodi')->insert([
            ['nama_prodi' => 'TRPL'],
            ['nama_prodi' => 'TR MANUFAKTUR'],
            ['nama_prodi' => 'TR MEKATRON'],
            ['nama_prodi' => 'T LISTRIK'],
        ]);
    }
}
