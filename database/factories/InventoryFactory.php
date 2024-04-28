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
        $satuan = $this->faker->randomElement(['pcs', 'pax', 'rim', 'buah']);

        return [
            'gambar_barang' => $this->faker->imageUrl(),
            'kode_barang' => $this->faker->numberBetween(0,2000),
            'nama_barang' => $this->faker->colorName(),
            'deskripsi_barang' => $this->faker->address(),
            'satuan' => $satuan,
            'stok_awal' => $stok_awal,
            'barang_keluar' => 0,
            'stok_akhir' => $stok_awal
        ];
    }
}
