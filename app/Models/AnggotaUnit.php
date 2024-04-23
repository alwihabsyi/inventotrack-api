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
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'unit_kerja_id';
    }
}
