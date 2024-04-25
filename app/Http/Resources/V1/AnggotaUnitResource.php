<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AnggotaUnitResource extends JsonResource
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
            'unitId' => $this->unit_kerja_id,
            'namaAnggota' => $this->nama_anggota,
            'jumlahBarang' => $this->jumlah_barang,
            'userRole' => $this->user_role,
            'userEmail' => $this->user_email
        ];
    }
}
