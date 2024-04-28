<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    public function anggotaUnits() {
        return $this->hasMany(AnggotaUnit::class);
    }

    public function statusPinjam() {
        return $this->hasMany(StatusPinjam::class);
    }

    public function calculateJumlahAnggota()
    {
        return $this->anggota()->count();
    }
}
