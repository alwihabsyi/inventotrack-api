<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreAnggotaUnitRequest;
use App\Http\Requests\UpdateAnggotaUnitRequest;
use App\Models\AnggotaUnit;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AnggotaUnitCollection;
use App\Http\Resources\V1\AnggotaUnitResource;

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
        //
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
}
