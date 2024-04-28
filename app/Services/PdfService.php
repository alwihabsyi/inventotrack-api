<?php

namespace App\Services;

use Dompdf\Dompdf;
use App\Models\Inventory;
use App\Models\TandaTangan;

class PdfService
{
    public function generatePdf($inventoryId, $jumlah)
    {
        $inventory = Inventory::find($inventoryId);
        $ttd = TandaTangan::find(1);

        if ($inventory) {
            $logoPath = public_path('images/logo_bps1.png');

            // Load HTML from blade template
            $html = view('pdf_template', compact('inventory', 'jumlah', 'ttd'))->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfContent = $dompdf->output();

            return $pdfContent;
        }
    }
}
