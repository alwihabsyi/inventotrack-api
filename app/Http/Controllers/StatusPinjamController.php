<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStatusPinjamRequest;
use App\Http\Requests\UpdateStatusPinjamRequest;
use App\Models\StatusPinjam;

class StatusPinjamController extends Controller
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
        //
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
}
