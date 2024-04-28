<?php

namespace App\Http\Resources\V1;

use App\Models\HistoryBarang;
use App\Models\Inventory;
use App\Models\UnitKerja;
use Illuminate\Http\Resources\Json\JsonResource;

class PengembalianResource extends JsonResource
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
            'data' =>$this->resource->map(function ($statusPinjam) use ($baseUrl) {
                $anggota = $statusPinjam->anggotaUnit;
                $inventory = Inventory::find($statusPinjam->inventory_id);
                $unitKerja = $statusPinjam->unitKerja;
                $historyBarang = HistoryBarang::where('status_pinjams_id', $statusPinjam->id)->first();

                return  [
                    'id' => $statusPinjam->id,
                    'namaAnggota' => $anggota->nama_anggota,
                    'namaUnit' => $unitKerja->nama_unit,
                    'namaBarang' => $inventory->nama_barang,
                    'kodeBarang' => $inventory->kode_barang,
                    'jumlahPinjam' => $statusPinjam->jumlah_pinjam,
                    'tanggalAmbil' => $statusPinjam->tanggal_ambil,
                    'userRole' => ucfirst($anggota->user_role),
                    'buktiAmbil' => $historyBarang ? $baseUrl . $historyBarang->bukti_ambil : null
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
            ]
        ];
    }
}
