<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function print(Payment $payment)
    {
        // Load the related entities needed for the receipt
        $payment->load(['invoice.student.programme', 'invoice.student.currentLevel', 'receipt', 'recordedBy']);

        $user = auth()->user();
        if ($user && $user->hasRole('Student') && $user->student && $payment->invoice->student_id !== $user->student->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('receipts.print', compact('payment'));
    }
}
