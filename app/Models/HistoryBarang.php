<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryBarang extends Model
{
    use HasFactory;

    public function inventories() {
        return $this->hasOne(Inventory::class);
    }

    public function anggotaUnit() {
        return $this->hasOne(AnggotaUnit::class);
    }
    
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
