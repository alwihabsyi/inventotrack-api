<?php

namespace App\Http\Resources\V1;

use App\Models\Inventory;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusPinjamResource extends JsonResource
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
            'data' => $this->resource->map(function ($statusPinjam) {
                return $this->formatStatusPinjam($statusPinjam);
            }),
            'links' => [
                'first' => $this->firstItem(),
                'last' => $this->lastItem(),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $this->currentPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ]
        ];
    }

    protected function formatStatusPinjam($statusPinjam)
    {
        $inventory = Inventory::find($statusPinjam->inventory_id);

        return [
            'id' => $statusPinjam->id,
            'namaBarang' => $inventory->nama_barang,
            'kodeBarang' => $inventory->kode_barang,
            'fotoBarang' => $inventory->gambar_barang,
            'jumlah' => $statusPinjam->jumlah_pinjam,
            'posisi' => $statusPinjam->posisi,
            'status' => $statusPinjam->status,
            'tanggalAjuan' => $statusPinjam->created_at
        ];
    }
}
