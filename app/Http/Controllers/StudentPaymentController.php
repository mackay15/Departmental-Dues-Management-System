<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentPaymentController extends Controller
{
    public function showPayForm(Invoice $invoice)
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('Student') || !$user->student || $invoice->student_id !== $user->student->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($invoice->balance <= 0) {
            return redirect()->route('invoices.show', $invoice)->with('info', 'This invoice is already fully paid.');
        }

        return view('student.payments.pay', compact('invoice'));
    }

    public function processPayment(Request $request, Invoice $invoice)
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('Student') || !$user->student || $invoice->student_id !== $user->student->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($invoice->balance <= 0) {
            return redirect()->route('invoices.show', $invoice)->with('error', 'Invoice is already fully paid.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $invoice->balance,
            'payment_method' => 'required|string|in:momo,card',
            
            // MoMo Validation
            'momo_provider' => 'required_if:payment_method,momo|nullable|string|in:mtn,telecel,at',
            'momo_number' => 'required_if:payment_method,momo|nullable|string|regex:/^[0-9]{10}$/',
            
            // Card Validation
            'card_number' => 'required_if:payment_method,card|nullable|string|regex:/^[0-9]{16}$/',
            'card_expiry' => 'required_if:payment_method,card|nullable|string|regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/',
            'card_cvv' => 'required_if:payment_method,card|nullable|string|regex:/^[0-9]{3}$/',
        ], [
            'momo_number.regex' => 'The mobile money number must be exactly 10 digits.',
            'card_number.regex' => 'The card number must be exactly 16 digits.',
            'card_expiry.regex' => 'The expiry date must be in MM/YY format.',
            'card_cvv.regex' => 'The CVV must be exactly 3 digits.',
        ]);

        $refPrefix = strtoupper($validated['payment_method']);
        $refNumber = $refPrefix . '-' . strtoupper(Str::random(10));
        
        $notes = '';
        if ($validated['payment_method'] === 'momo') {
            $notes = 'Momo payment via ' . strtoupper($validated['momo_provider']) . ' (' . $validated['momo_number'] . ')';
        } else {
            $maskedCard = 'XXXX-XXXX-XXXX-' . substr($validated['card_number'], -4);
            $notes = 'Card payment via ' . $maskedCard;
        }

        DB::transaction(function () use ($validated, $invoice, $refNumber, $notes) {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $validated['amount'],
                'payment_date' => now(),
                'payment_method' => $validated['payment_method'],
                'reference_number' => $refNumber,
                'recorded_by' => Auth::id(),
                'notes' => $notes,
                'status' => 'completed',
            ]);

            // Generate a Receipt
            Receipt::create([
                'payment_id' => $payment->id,
                'receipt_number' => 'RCT-' . strtoupper(Str::random(8)),
                'issued_by' => null, // Self-service online payment
            ]);

            // Update Invoice totals
            $invoice->paid_amount += $validated['amount'];
            $invoice->balance -= $validated['amount'];
            
            if ($invoice->balance <= 0) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'partial';
            }
            $invoice->save();
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Your online payment was completed successfully!');
    }
}
