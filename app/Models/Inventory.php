<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function anggota() {
        return $this->belongsTo(AnggotaUnit::class);
    }

    public function statusPinjam() {
        return $this->belongsTo(StatusPinjam::class);
    }

    public function historyBarang() {
        return $this->belongsTo(HistoryBarang::class);
    }
}
