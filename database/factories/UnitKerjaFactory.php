<?php

namespace Database\Factories;

use App\Models\AnggotaUnit;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitKerjaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $namaUnit = "Unit " . $this->faker->firstName();

        return [
            'nama_unit' => $namaUnit,
            'jumlah_anggota' => $this->faker->numberBetween(10,20)
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (UnitKerja $unitKerja) {
            $jumlahAnggota = $unitKerja->calculateJumlahAnggota();
            $unitKerja->update(['jumlah_anggota' => $jumlahAnggota]);
        });
    }
}
