<?php

namespace App\Services;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ExcelImport implements ToModel, WithUpserts, WithStartRow
{
    public function model(array $row)
    {
        return new Inventory([
            'kode_barang' => $row[0],
            'nama_barang' => $row[1],
            'deskripsi_barang' => $row[2],
            'satuan' => $row[3],
            'stok_awal' => $row[4],
            'barang_keluar' => $row[5],
            'stok_akhir' => $row[6],
        ]);
    }

    public function uniqueBy()
    {
        return 'nama_barang';
    }

    public function startRow(): int
    {
        return 2;
    }
}

?>