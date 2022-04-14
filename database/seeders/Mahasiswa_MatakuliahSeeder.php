<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Mahasiswa_MatakuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $khs = [
            [
                'mahasiswa_id' => 5,
                'matakuliah_id' => 1,
                'nilai' => 'A',
            ],
            [
                'mahasiswa_id' => 5,
                'matakuliah_id' => 2,
                'nilai' => 'A',
            ],
            [
                'mahasiswa_id' => 5,
                'matakuliah_id' => 3,
                'nilai' => 'A',
            ],
            [
                'mahasiswa_id' => 5,
                'matakuliah_id' => 4,
                'nilai' => 'A',
            ],

            [
                'mahasiswa_id' => 6,
                'matakuliah_id' => 1,
                'nilai' => 'A',
            ],
            [
                'mahasiswa_id' => 6,
                'matakuliah_id' => 2,
                'nilai' => 'A',
            ],
            [
                'mahasiswa_id' => 6,
                'matakuliah_id' => 3,
                'nilai' => 'A',
            ],
            [
                'mahasiswa_id' => 6,
                'matakuliah_id' => 4,
                'nilai' => 'A',
            ],

            [
                'mahasiswa_id' => 10,
                'matakuliah_id' => 1,
                'nilai' => 'A',
            ],
            [
                'mahasiswa_id' => 10,
                'matakuliah_id' => 2,
                'nilai' => 'A',
            ],
            [
                'mahasiswa_id' => 10,
                'matakuliah_id' => 3,
                'nilai' => 'A',
            ],
            [
                'mahasiswa_id' => 10,
                'matakuliah_id' => 4,
                'nilai' => 'A',
            ],



        ];

        DB::table('mahasiswa_matakuliah')->insert($khs);
    }
}
