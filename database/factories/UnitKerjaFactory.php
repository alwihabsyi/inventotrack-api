<?php

namespace Database\Factories;

use App\Models\AnggotaUnit;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitKerjaFactory extends Factory
{
    protected static $firstName = [
        'Agus', 'Anton', 'Budi', 'Dodi', 'Eko', 'Firman', 'Ganda', 'Hadi', 'Indra', 'Joko',
        'Krisna', 'Lukman', 'Maman', 'Nur', 'Opik', 'Prabowo', 'Rudi', 'Sigit', 'Tono', 'Yoga',
        'Ani', 'Bunga', 'Citra', 'Desi', 'Eva', 'Fitri', 'Gita', 'Hani', 'Ina', 'Juni',
        'Kartika', 'Lina', 'Mira', 'Nina', 'Rina', 'Sari', 'Tika', 'Yuni', 'Wati', 'Zahra'
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $namaUnit = "Unit " . $this->faker->randomElement(static::$firstName);

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
