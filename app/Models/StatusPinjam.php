<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPinjam extends Model
{
    use HasFactory;

    public function inventories() {
        return $this->hasMany(Inventory::class);
    }

    public function anggotaUnit() {
        return $this->belongsTo(AnggotaUnit::class);
    }

    public function unitKerja() {
        return $this->belongsTo(UnitKerja::class);
    }
}
