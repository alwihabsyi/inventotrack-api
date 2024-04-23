<?php

use App\Http\Controllers\Api\V1\LaporanController;
use App\Http\Controllers\Api\V1\AnggotaUnitController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\StatusPinjamController;
use App\Http\Controllers\Api\V1\UnitKerjaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {
    Route::apiResource('inventories', InventoryController::class);
    Route::apiResource('anggota', AnggotaUnitController::class)->parameters(['anggota' => 'anggotaUnit']);;
    Route::apiResource('unit', UnitKerjaController::class)->parameters(['unit' => 'unitKerja']);;
    Route::apiResource('statusPinjam', StatusPinjamController::class);
    Route::post('ajukanBarang', [InventoryController::class, 'ajukanBarang']);
    Route::get('laporanKetua/{anggotaUnit}', [LaporanController::class, 'getLaporanKetua']);
    Route::get('laporanAdmin', [LaporanController::class, 'getLaporanAdmin']);
    Route::put('approveKetua/{id}/update', [StatusPinjamController::class, 'approveKetua']);
    Route::put('tolakKetua/{id}/update', [StatusPinjamController::class, 'tolakKetua']);
    Route::put('approveAdmin/{id}/update', [StatusPinjamController::class, 'approveAdmin']);
    Route::put('tolakAdmin/{id}/update', [StatusPinjamController::class, 'tolakAdmin']);
});