<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generateInvoice($id)
    {

        $order = Order::with(['user', 'products'])->findOrFail($id);

        $pdf = Pdf::loadView('invoice', ['order' => $order]);
        $pdfOutput = $pdf->output();

        return response($pdfOutput, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="invoice.pdf"');
    }
}
