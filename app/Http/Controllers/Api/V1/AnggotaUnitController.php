<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreAnggotaUnitRequest;
use App\Http\Requests\UpdateAnggotaUnitRequest;
use App\Models\AnggotaUnit;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AnggotaUnitCollection;
use App\Http\Resources\V1\AnggotaUnitResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AnggotaUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new AnggotaUnitCollection(AnggotaUnit::paginate());
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
     * @param  \App\Http\Requests\StoreAnggotaUnitRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnggotaUnitRequest $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AnggotaUnit  $anggotaUnit
     * @return \Illuminate\Http\Response
     */
    public function show(AnggotaUnit $anggotaUnit)
    {
        return new AnggotaUnitResource($anggotaUnit);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AnggotaUnit  $anggotaUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(AnggotaUnit $anggotaUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAnggotaUnitRequest  $request
     * @param  \App\Models\AnggotaUnit  $anggotaUnit
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnggotaUnitRequest $request, AnggotaUnit $anggotaUnit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AnggotaUnit  $anggotaUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(AnggotaUnit $anggotaUnit)
    {
        //
    }

    public function addAnggota()
    {
        $validator = Validator::make(request()->all(), [
            'unit_kerja_id' => 'required|integer',
            'nama_anggota' => 'required|string',
            'user_role' => 'required|string',
            'user_email' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Bad Request. Missing required parameters.'], 400);
        }

        $unitKerja = request()->input('unit_kerja_id');
        $namaAnggota = request()->input('nama_anggota');
        $userRole = request()->input('user_role');
        $userEmail = request()->input('user_email');

        $anggota = AnggotaUnit::where('nama_anggota', $namaAnggota)->first();

        if ($anggota !== null) {
            return response()->json(['status' => 'failed', 'error' => 'Nama anggota sudah terdaftar.'], 400);
        }

        DB::beginTransaction();
        try {
            AnggotaUnit::create([
                'unit_kerja_id' => $unitKerja,
                'nama_anggota' => $namaAnggota,
                'user_role' => $userRole,
                'user_email' => $userEmail,
                'jumlah_barang' => 0
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Berhasil daftar, silahkan login'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    public function signIn($userEmail)
    {
        $validator = Validator::make(['user_email' => $userEmail], [
            'user_email' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Bad Request. Missing required parameters.'], 400);
        }

        $anggota = AnggotaUnit::where('user_email', $userEmail)->first();
        if ($anggota) {
            return response()->json(['status' => 'success', 'message' => 'Berhasil masuk', 'data' => new AnggotaUnitResource($anggota)], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Terjadi kesalahan', 'error' => "User tidak terdaftar"], 500);
        }
    }

}
