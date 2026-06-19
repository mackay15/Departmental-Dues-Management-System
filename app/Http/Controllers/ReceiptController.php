<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function download(Payment $payment)
    {
        // Load the related entities needed for the receipt
        $payment->load(['invoice.student.programme', 'invoice.student.currentLevel', 'receipt', 'recordedBy']);

        // Generate the PDF
        $pdf = Pdf::loadView('receipts.pdf', compact('payment'));

        // Return the PDF as a download
        $filename = 'Receipt_' . ($payment->receipt->receipt_number ?? $payment->reference_number) . '.pdf';
        
        return $pdf->download($filename);
    }
}
