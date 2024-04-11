<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnitKerja::factory()
            ->count(30)
            ->hasAnggota(10)
            ->create();

        UnitKerja::factory()
            ->count(40)
            ->hasAnggota(20)
            ->create();
    }
}
