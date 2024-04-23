<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Posisi;
use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatusPinjamRequest;
use App\Http\Requests\UpdateStatusPinjamRequest;
use App\Http\Resources\V1\StatusPinjamCollection;
use App\Http\Resources\V1\StatusPinjamResource;
use App\Models\StatusPinjam;
use Illuminate\Support\Facades\DB;
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
        $validator = Validator::make(request()->all(), [
            'ttd_ketua' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Bad Request. Missing required parameters.'], 400);
        }

        $signature = request()->input('ttd_ketua');

        DB::beginTransaction();
        try {
            $statusPinjam = StatusPinjam::findOrFail($id);
            $statusPinjam->posisi = Posisi::APPROVE_KETUA;
            $statusPinjam->status = Statuses::DITERIMA_KETUA;
            $statusPinjam->save();

            $statusPinjam->ttd_ketua = $signature;
            $statusPinjam->save();

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
            $statusPinjam->save();

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
            'ttd_admin' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Bad Request. Missing required parameters.'], 400);
        }

        $signature = request()->input('ttd_admin');

        DB::beginTransaction();
        try {
            $statusPinjam = StatusPinjam::findOrFail($id);
            $statusPinjam->posisi = Posisi::APPROVE_ADMIN;
            $statusPinjam->status = Statuses::DITERIMA_ADMIN;
            $statusPinjam->save();

            $statusPinjam->ttd_admin = $signature;
            $statusPinjam->save();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Peminjaman di setujui'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi kesalahan upload ke server', 'error' => $e->getMessage()], 500);
        }
    }
}
