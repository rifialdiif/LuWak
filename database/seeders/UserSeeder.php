<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user')->insert([
            [
                'nama' => 'Mas Voldi',
                'email' => 'admin1@example.com',
                'password_hash' => bcrypt('password'),
                'role' => 'Admin',
                'nip_nim' => '20220001',
                'id_prodi' => 1,
            ],
            [
                'nama' => 'Dosen Baik',
                'email' => 'dosen2@example.com',
                'password_hash' => bcrypt('password'),
                'role' => 'DPA',
                'nip_nim' => '19870002',
                'id_prodi' => 2,
            ],
            [
                'nama' => 'Gaia',
                'email' => 'mahasiswa3@example.com',
                'password_hash' => bcrypt('password'),
                'role' => 'Mahasiswa',
                'nip_nim' => '20221003',
                'id_prodi' => 1,
            ],
            [
                'nama' => 'Empat',
                'email' => 'mahasiswa4@example.com',
                'password_hash' => bcrypt('password'),
                'role' => 'Mahasiswa',
                'nip_nim' => '20221004',
                'id_prodi' => 2,
            ],
            [
                'nama' => 'Lima',
                'email' => 'dosen5@example.com',
                'password_hash' => bcrypt('password'),
                'role' => 'DPA',
                'nip_nim' => '19870005',
                'id_prodi' => 1,
            ],
        ]);
    }
}
