<?php

namespace App\Http\Resources\V1;

use App\Models\Inventory;
use App\Models\StatusPinjam;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorResource extends JsonResource
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
            'data' =>$this->resource->map(function ($unitKerja) {
                $jumlahPinjam = StatusPinjam::where('unit_kerja_id', $unitKerja->id)->sum('jumlah_pinjam');
                $anggota = $unitKerja->anggotaUnits;

                return  [
                    'id' => $unitKerja->id,
                    'namaUnit' => $unitKerja->nama_unit,
                    'jumlahAnggota' => $unitKerja->jumlah_anggota,
                    'jumlahPinjam' => $jumlahPinjam,
                    'anggota' => $this->toAnggotas($anggota, $unitKerja->nama_unit)
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

    public function toAnggotas($anggota, $namaUnit)
    {
        return $anggota->map(function ($anggotaUnit) use ($namaUnit) {
            $jumlahPinjam = StatusPinjam::where('anggota_unit_id', $anggotaUnit->id)->sum('jumlah_pinjam');
            $pinjam = StatusPinjam::where('anggota_unit_id', $anggotaUnit->id)->get();

            return [
                'namaAnggota' => $anggotaUnit->nama_anggota,
                'roleAnggota' => ucfirst($anggotaUnit->user_role),
                'unitKerja' => $namaUnit,
                'jumlahPinjam' => $jumlahPinjam,
                'barangDipinjam' => $this->toStatusPinjam($pinjam)
            ];
        })->toArray();
    }

    public function toStatusPinjam($statusPinjam)
    {
        return $statusPinjam->map(function ($pinjam) {
            $inventory = Inventory::find($pinjam->inventory_id);
            $baseUrl = config('app.url');
            return [
                'gambarBarang' => $baseUrl . $inventory->gambar_barang,
                'namaBarang' => $inventory->nama_barang,
                'tanggalAjuan' => $pinjam->created_at,
                'tanggalAmbil' => $pinjam->tanggal_ambil,
                'jumlah' => $pinjam->jumlah_pinjam,
                'status' => $pinjam->status
            ];
        });
    }
}
