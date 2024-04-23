<?php

namespace App\Http\Resources\V1;

use App\Models\StatusPinjam;
use App\Models\UnitKerja;
use Illuminate\Http\Resources\Json\JsonResource;

class LaporanResource extends JsonResource
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
            'data' => $this->resource->map(function ($anggotaUnit) {
                $unit = UnitKerja::find($anggotaUnit->unit_kerja_id);
                
                $jumlahPinjam = StatusPinjam::where('anggota_unit_id', $anggotaUnit->id)->sum('jumlah_pinjam');

                return [
                    'id' => $anggotaUnit->id,
                    'unitId' => $anggotaUnit->unit_kerja_id,
                    'namaUnit' => $unit->nama_unit,
                    'namaAnggota' => $anggotaUnit->nama_anggota,
                    'jumlahBarang' => $jumlahPinjam
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
