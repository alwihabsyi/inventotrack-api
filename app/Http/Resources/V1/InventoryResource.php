<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'gambarBarang' => $this->gambar_barang,
            'kodeBarang' => $this->kode_barang,
            'namaBarang' => $this->nama_barang,
            'stokAwal' => $this->stok_awal,
            'barangKeluar' => $this->barang_keluar,
            'stokAkhir' => $this->stok_akhir
        ];
    }
}
