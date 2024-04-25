<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnggotaUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_anggota' => $this->faker->name(),
            'jumlah_barang' => 0,
            'user_email' => $this->faker->email(),
            'user_role' => $this->faker->randomElement(['anggota', 'ketua', 'admin', 'kepala'])
        ];
    }
}
