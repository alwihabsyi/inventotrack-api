<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Posisi;
use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Http\Resources\V1\InventoryCollection;
use App\Http\Resources\V1\InventoryResource;
use App\Models\AnggotaUnit;
use App\Models\Inventory;
use App\Models\Notification;
use App\Models\StatusPinjam;
use App\Models\UnitKerja;
use App\Services\ExcelImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = request()->query('limit', 10);
        return new InventoryCollection(Inventory::paginate($limit));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInventoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInventoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        return new InventoryResource($inventory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInventoryRequest  $request
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        //
    }

    public function ajukanBarang()
    {
        $validator = Validator::make(request()->all(), [
            'inventoryId' => 'required|integer',
            'jumlah' => 'required|integer',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Bad Request. Missing required parameters.'], 400);
        }

        $inventoryId = request()->input('inventoryId');
        $jumlah = request()->input('jumlah');
        $user_id = request()->input('user_id');
        $user = AnggotaUnit::find($user_id);
        $unitKerja = UnitKerja::find($user->unit_kerja_id);

        $item = Inventory::find($inventoryId);

        if ($item->stok_akhir >= $jumlah) {
            DB::beginTransaction();
            try {
                $item->stok_akhir = $item->stok_akhir - $jumlah;
                $item->barang_keluar = $item->barang_keluar + $jumlah;
                $item->save();

                StatusPinjam::create([
                    'inventory_id' => $inventoryId,
                    'anggota_unit_id' => $user_id,
                    'unit_kerja_id' => $unitKerja->id,
                    'jumlah_pinjam' => $jumlah,
                    'tanggal_pinjam' => Carbon::now(),
                    'posisi' => Posisi::AJUAN_ANGGOTA,
                    'status' => Statuses::PENDING
                ]);

                Notification::create([
                    'unit_id' => $unitKerja->id,
                    'user_role' => 'ketua',
                    'title' => 'Ajuan pengambilan barang',
                    'description' => 'Setujui pengambilan barang',
                    'is_unread' => true,
                ]);

                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Ajuan berhasil dikirim'], 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => 'Terjadi kesalahan upload ke server', 'error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['message' => 'Stok tidak mencukupi'], 400);
        }
    }

    public function batchUploadData()
    {
        $validator = Validator::make(request()->all(), [
            'excel' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json(['failed' => $validator->errors()], 400);
        }

        if (request()->hasFile('excel')) {
            try {
                $file = request()->file('excel');
                $file->move('uploads', $file->getClientOriginalName());
                Excel::import(new ExcelImport(), public_path('uploads/' . $file->getClientOriginalName()));
                return response()->json(['status' => 'success', 'message' => 'Data berhasil diimpor'], 200);
            } catch (\Throwable $e) {
                return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Tidak ada file excel'], 400);
        }
    }

    public function editItemImage()
    {
        $validator = Validator::make(request()->all(), [
            'gambarBarang' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'inventoryId' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        if (request()->hasFile('gambarBarang')) {
            $inventoryId = request()->input('inventoryId');
            $gambarBarang = request()->file('gambarBarang');
            $imageName = time() . '_' . $gambarBarang->getClientOriginalName();
            $gambarBarang->storeAs('images', $imageName, 'public');
            $imageUrl = '/storage/images/' . $imageName;

            DB::beginTransaction();
            try {
                $inventory = Inventory::findOrFail($inventoryId);
                $inventory->gambar_barang = $imageUrl;
                $inventory->save();

                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Berhasil edit gambar'], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['status' => 'failed', 'message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Image not found'], 400);
    }

    public function getDashboard()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $stokAwal = Inventory::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('stok_awal');
            $barangKeluar = Inventory::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('barang_keluar');

            $monthlyData[] = [
                'month' => $month,
                'stokAwal' => $stokAwal,
                'barangKeluar' => $barangKeluar,
            ];
        }

        $stokAwal = Inventory::whereMonth('created_at', $currentMonth)->sum('stok_awal');
        $barangKeluar = Inventory::whereMonth('created_at', $currentMonth)->sum('barang_keluar');
        $stokAkhir = Inventory::whereMonth('created_at', $currentMonth)->sum('stok_akhir');

        $topItems = Inventory::whereMonth('created_at', $currentMonth)
            ->where('barang_keluar', '>', 0)
            ->orderBy('barang_keluar', 'desc')
            ->take(5)
            ->get();

        $topItemsData = [];
        foreach ($topItems as $item) {
            $topItemsData[] = [
                'namaBarang' => $item->nama_barang,
            ];
        }

        return response()->json(['status' => 'success', 'data' => [
            'stokAwal' => $stokAwal,
            'barangKeluar' => $barangKeluar,
            'stokAkhir' => $stokAkhir,
            'topItems' => $topItemsData,
            'monthlyData' => $monthlyData
        ]], 200);
    }
}
