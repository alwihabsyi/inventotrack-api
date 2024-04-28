<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHistoryBarangRequest;
use App\Http\Requests\UpdateHistoryBarangRequest;
use App\Http\Resources\V1\HistoryResource;
use App\Http\Resources\V1\PengembalianResource;
use App\Models\AnggotaUnit;
use App\Models\HistoryBarang;
use App\Models\Inventory;
use App\Models\StatusPinjam;
use App\Services\PdfService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Facade\FlareClient\Http\Exceptions\NotFound;
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
            'fotoBukti' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'inventoryId' => 'required|integer',
            'statusId' => 'required|integer',
            'userId' => 'required|integer',
            'tanggalAmbil' => 'required|string',
            'jumlah' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        if (request()->hasFile('fotoBukti')) {
            $image = request()->file('fotoBukti');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('images', $imageName, 'public');
            $imageUrl = '/storage/images/' . $imageName;

            DB::beginTransaction();
            try {
                // $tanggalAmbil = Carbon::createFromFormat('d F Y', request()->input('tanggalAmbil'))->toDateString();
                HistoryBarang::create([
                    'inventory_id' => request()->input('inventoryId'),
                    'status_pinjams_id' => request()->input('statusId'),
                    'anggota_unit_id' => request()->input('userId'),
                    'bukti_ambil' => $imageUrl,
                    'tanggal_ambil' => request()->input('tanggalAmbil')
                ]);

                $statusPinjam = StatusPinjam::findOrFail(request()->input('statusId'));
                $statusPinjam->status = Statuses::SELESAI;
                $statusPinjam->save();

                DB::commit();
                $pdfService = new PdfService();
                $pdfContent = $pdfService->generatePdf(request()->input('inventoryId'), request()->input('jumlah'));

                $pdfFileName = time() . '_bukti_ambil_'. request()->input('statusId') .'.pdf';
                Storage::disk('public')->put('pdfs/' . $pdfFileName, $pdfContent);
                $pdfUrl = config('app.url') . '/storage/pdfs/' . $pdfFileName;

                $data = [
                    'buktiCetak' => $pdfUrl
                ];

                return response()->json(['status' => 'success', 'data' => $data], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Image not found'], 400);
    }

    public function getUserHistory($userId)
    {
        $limit = request()->query('limit', 10);
        $user = AnggotaUnit::find($userId);
        
        if (!$user) {
            abort(404, 'User not found');
        }

        $historyBarang = HistoryBarang::where('anggota_unit_id', $userId)->paginate($limit);
        return new HistoryResource($historyBarang);
    }
}
