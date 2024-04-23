<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Posisi;
use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Http\Resources\V1\InventoryCollection;
use App\Http\Resources\V1\InventoryResource;
use App\Models\Inventory;
use App\Models\StatusPinjam;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function ajukanBarang() {
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

        $item = Inventory::find($inventoryId);
        
        if ($item->stok_akhir >= $jumlah) {
            DB::beginTransaction();
            try {
                StatusPinjam::create([
                    'inventory_id' => $inventoryId,
                    'anggota_unit_id' => $user_id,
                    'jumlah_pinjam' => $jumlah,
                    'tanggal_pinjam' => Carbon::now(),
                    'posisi' => Posisi::AJUAN_ANGGOTA,
                    'status' => Statuses::PENDING  
                ]);

                DB::commit();
                return response()->json(['status' => 'success' , 'message' => 'Ajuan berhasil dikirim'], 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => 'Terjadi kesalahan upload ke server', 'error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['message' => 'Stok tidak mencukupi'], 400);
        }
    }
}
