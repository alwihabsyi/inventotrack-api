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
    
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'anggota_unit_id';
    }

}
