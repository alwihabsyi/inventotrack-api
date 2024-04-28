<?php

namespace Database\Seeders;

use App\Models\TandaTangan;
use Illuminate\Database\Seeder;

class TandaTanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TandaTangan::factory()
            ->count(1)
            ->create();
    }
}
