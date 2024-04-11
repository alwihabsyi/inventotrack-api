<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaUnit extends Model
{
    use HasFactory;

    public function unitKerja() {
        return $this->belongsTo(UnitKerja::class);
    }

    public function inventories() {
        return $this->hasMany(Inventory::class);
    }

    public function statusPinjam() {
        return $this->hasMany(StatusPinjam::class);
    }
}
