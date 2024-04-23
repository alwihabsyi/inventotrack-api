<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\LaporanResource;
use App\Models\AnggotaUnit;
use Illuminate\Http\Request;

class LaporanController extends Controller
{

    public function getLaporanKetua(AnggotaUnit $anggotaUnit)
    {
        $limit = request()->query('limit', 5);

        $status = Statuses::PENDING;
        if (request()->input('posisi')) {
            switch (request()->input('posisi')) {
                case 'Ajuan':
                    $status = Statuses::PENDING;
                    break;

                case 'Diterima':
                    $status = [Statuses::DITERIMA_KETUA, Statuses::DITERIMA_ADMIN];
                    break;

                case 'Ditolak':
                    $status = Statuses::DITOLAK;
                    break;

                default:
                    $status = Statuses::PENDING;
                    break;
            }
        }

        $anggotaUnit = AnggotaUnit::where('unit_kerja_id', $anggotaUnit->unit_kerja_id)
            ->whereHas('statusPinjam', function ($query) use ($status) {
                $query->where('jumlah_pinjam', '>', 0);
                $query->whereIn('status', (array)$status);
            })
            ->paginate($limit);
        return new LaporanResource($anggotaUnit);
    }

    public function getLaporanAdmin(AnggotaUnit $anggotaUnit)
    {
        $limit = request()->query('limit', 5);

        $status = Statuses::PENDING;
        if (request()->input('posisi')) {
            switch (request()->input('posisi')) {
                case 'Ajuan':
                    $status = Statuses::DITERIMA_KETUA;
                    break;

                case 'Diterima':
                    $status = Statuses::DITERIMA_ADMIN;
                    break;

                case 'Ditolak':
                    $status = Statuses::DITOLAK;
                    break;

                default:
                    $status = Statuses::DITERIMA_KETUA;
                    break;
            }
        }

        $anggotaUnit = AnggotaUnit::whereHas('statusPinjam', function ($query) use ($status) {
            $query->where('jumlah_pinjam', '>', 0);
            $query->where('status', $status);
        })
            ->paginate($limit);
        return new LaporanResource($anggotaUnit);
    }
}
