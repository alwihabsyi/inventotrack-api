<?php

namespace App\Http\Resources\V1;

use App\Models\Inventory;
use App\Models\StatusPinjam;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $baseUrl = config('app.url');

        return [
            'data' => $this->resource->map(function ($historyBarang) use ($baseUrl) {
                $statusPinjam = StatusPinjam::find($historyBarang->status_pinjams_id);
                $inventory = Inventory::find($statusPinjam->inventory_id);

                return [
                    'id' => $historyBarang->id,
                    'namaBarang' => $inventory->nama_barang,
                    'deskripsiBarang' => $inventory->deskripsi_barang,
                    'kodeBarang' => $inventory->kode_barang,
                    'jumlahBarang' => $statusPinjam->jumlah_pinjam,
                    'tanggalAmbil' => $historyBarang->tanggal_ambil,
                    'gambarBarang' => $baseUrl . $inventory->gambar_barang,
                    'buktiAmbil' => $baseUrl . $historyBarang->bukti_ambil
                ];
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
            ],
        ];
    }
}
