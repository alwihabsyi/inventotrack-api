<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $stok_awal = $this->faker->numberBetween(0,200);

        return [
            'gambar_barang' => $this->faker->imageUrl(),
            'kode_barang' => $this->faker->numberBetween(0,2000),
            'nama_barang' => $this->faker->colorName(),
            'stok_awal' => $stok_awal,
            'barang_keluar' => 0,
            'stok_akhir' => $stok_awal
        ];
    }
}
