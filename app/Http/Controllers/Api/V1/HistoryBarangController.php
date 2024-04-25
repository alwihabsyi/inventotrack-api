<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHistoryBarangRequest;
use App\Http\Requests\UpdateHistoryBarangRequest;
use App\Models\HistoryBarang;
use App\Models\StatusPinjam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HistoryBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreHistoryBarangRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHistoryBarangRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HistoryBarang  $historyBarang
     * @return \Illuminate\Http\Response
     */
    public function show(HistoryBarang $historyBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HistoryBarang  $historyBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(HistoryBarang $historyBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateHistoryBarangRequest  $request
     * @param  \App\Models\HistoryBarang  $historyBarang
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHistoryBarangRequest $request, HistoryBarang $historyBarang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HistoryBarang  $historyBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(HistoryBarang $historyBarang)
    {
        //
    }

    public function cetakBuktiAmbilBarang()
    {
        $validator = Validator::make(request()->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('images', $imageName, 'public');
            $imageUrl = Storage::url($imageName);

            DB::beginTransaction();
            try {
                HistoryBarang::create([
                    'inventory_id' => request()->input('inventoryId'),
                    'status_pinjams_id' => request()->input('statusId'),
                    'bukti_ambil' => $imageUrl,
                    'tanggal_ambil' => request()->input('tanggalAmbil')
                ]);

                $statusPinjam = StatusPinjam::findOrFail(request()->input('statusId'));
                $statusPinjam->status = Statuses::SELESAI;
                $statusPinjam->save();

                DB::commit();

                // Kirim bukti ambil dengan ttd ke user
                return response()->json(['status' => 'success', 'message' => 'Barang berhasil ditolak'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 500);
            }
            
            $historyBarang = $this->find(request()->input('id'));
            $historyBarang->image_url = $imageUrl;
            $historyBarang->save();

            return response()->json(['url' => $imageUrl]);
        }

        return response()->json(['error' => 'Image not found'], 400);
    }
}
