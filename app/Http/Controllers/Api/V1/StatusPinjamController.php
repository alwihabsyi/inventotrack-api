<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Posisi;
use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatusPinjamRequest;
use App\Http\Requests\UpdateStatusPinjamRequest;
use App\Http\Resources\V1\PengembalianResource;
use App\Http\Resources\V1\StatusPinjamCollection;
use App\Http\Resources\V1\StatusPinjamResource;
use App\Models\Inventory;
use App\Models\Notification;
use App\Models\StatusPinjam;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StatusPinjamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new StatusPinjamCollection(StatusPinjam::paginate());
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
     * @param  \App\Http\Requests\StoreStatusPinjamRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStatusPinjamRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StatusPinjam  $statusPinjam
     * @return \Illuminate\Http\Response
     */
    public function show(StatusPinjam $statusPinjam)
    {
        $limit = request()->query('limit', 10);
        $statusPinjams = StatusPinjam::where('anggota_unit_id', $statusPinjam->anggota_unit_id)->paginate($limit);
        return new StatusPinjamResource($statusPinjams);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StatusPinjam  $statusPinjam
     * @return \Illuminate\Http\Response
     */
    public function edit(StatusPinjam $statusPinjam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStatusPinjamRequest  $request
     * @param  \App\Models\StatusPinjam  $statusPinjam
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStatusPinjamRequest $request, StatusPinjam $statusPinjam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StatusPinjam  $statusPinjam
     * @return \Illuminate\Http\Response
     */
    public function destroy(StatusPinjam $statusPinjam)
    {
        //
    }

    public function approveKetua($id)
    {
        $validator = Validator::make(request()->input(), [
            'jumlahBarang' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Bad Request. Missing required parameters.'], 400);
        }

        DB::beginTransaction();
        try {
            $statusPinjam = StatusPinjam::findOrFail($id);

            $inventory = Inventory::find($statusPinjam->inventory_id);
            if (request()->input('jumlahBarang') > $inventory->stok_akhir) {
                return response()->json(['status' => 'failed', 'message' => 'Stok tidak mencukupi. stok tersisa = ' . $inventory->stok_akhir], 400);
            }

            $statusPinjam->posisi = Posisi::APPROVE_KETUA;
            $statusPinjam->status = Statuses::DITERIMA_KETUA;
            $statusPinjam->save();

            Notification::create([
                'unit_id' => $statusPinjam->unit_kerja_id,
                'user_role' => 'admin',
                'title' => 'Approval peminjaman barang',
                'description' => 'Setujui pengambilan barang',
                'is_unread' => true,
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Peminjaman di setujui'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi kesalahan upload ke server', 'error' => $e->getMessage()], 500);
        }
    }

    public function tolakKetua($id)
    {
        DB::beginTransaction();
        try {
            $statusPinjam = StatusPinjam::findOrFail($id);
            $statusPinjam->status = Statuses::DITOLAK;
            $statusPinjam->jumlah = 0;
            $statusPinjam->save();

            $inventory = Inventory::find($statusPinjam->inventory_id);
            $inventory->stok_akhir = $inventory->stok_akhir + $statusPinjam->jumlah_pinjam;
            $inventory->barang_keluar = $inventory->barang_keluar - $statusPinjam->jumlah_pinjam;
            $inventory->save();

            Notification::create([
                'anggota_unit_id' => $statusPinjam->anggota_unit_id,
                'unit_id' => $statusPinjam->unit_kerja_id,
                'user_role' => 'anggota',
                'title' => 'Pengajuan barang ditolak',
                'description' => 'Ajuan untuk barang ' . $inventory->nama_barang . ' ditolak',
                'is_unread' => true,
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Barang berhasil ditolak'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi kesalahan upload ke server', 'error' => $e->getMessage()], 500);
        }
    }

    public function approveAdmin($id)
    {
        $validator = Validator::make(request()->all(), [
            'jumlahBarang' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Bad Request. Missing required parameters.'], 400);
        }
        
        DB::beginTransaction();
        try {
            $statusPinjam = StatusPinjam::findOrFail($id);

            $inventory = Inventory::find($statusPinjam->inventory_id);
            if (request()->input('jumlahBarang') > $inventory->stok_akhir) {
                return response()->json(['status' => 'failed', 'message' => 'Stok tidak mencukupi. stok tersisa = ' . $inventory->stok_akhir], 400);
            }
            
            $statusPinjam->posisi = Posisi::APPROVE_ADMIN;
            $statusPinjam->status = Statuses::DITERIMA_ADMIN;
            $statusPinjam->tanggal_ambil = Carbon::now()->addDay();
            $statusPinjam->save();

            Notification::create([
                'anggota_unit_id' => $statusPinjam->anggota_unit_id,
                'unit_id' => $statusPinjam->unit_kerja_id,
                'user_role' => 'anggota',
                'title' => 'Pengajuan barang disetujui admin',
                'description' => 'Ajuan untuk barang ' . $inventory->nama_barang . ' telah disetujui admin',
                'is_unread' => true,
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Peminjaman di setujui'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi kesalahan upload ke server', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAllUserHistory()
    {
        $limit = request()->query('limit', 10);
        $tanggalAmbil = request()->query('tanggalAmbil', null);
        $historyBarang = StatusPinjam::where('status', Statuses::SELESAI);

        if ($tanggalAmbil !== null) {
            $historyBarang->whereDate('tanggal_ambil', $tanggalAmbil);
        }

        return new PengembalianResource($historyBarang->paginate($limit));
    }
}
