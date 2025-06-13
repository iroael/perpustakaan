<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create('id_ID');

        foreach (range(1, 20) as $i) {
            DB::table('tblm_members')->insert([
                'no_anggota' => 'AGT' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama' => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tanggal_lahir' => $faker->date(),
                'alamat' => $faker->address,
                'photo' => null, // Atau path default jika ada
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
