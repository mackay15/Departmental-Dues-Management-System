<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StudentPaymentController extends Controller
{
    /**
     * Show the Paystack checkout page for an invoice.
     */
    public function showPayForm(Invoice $invoice)
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('Student') || !$user->student || $invoice->student_id !== $user->student->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($invoice->balance <= 0) {
            return redirect()->route('invoices.show', $invoice)->with('info', 'This invoice is already fully paid.');
        }

        $invoice->load(['student', 'academicSession', 'items.due']);

        $paystackPublicKey = config('services.paystack.public_key');
        $currency          = config('services.paystack.currency', 'GHS');

        return view('student.payments.pay', compact('invoice', 'paystackPublicKey', 'currency'));
    }

    /**
     * Verify a completed Paystack transaction and record the payment.
     */
    public function verifyPayment(Request $request, Invoice $invoice)
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('Student') || !$user->student || $invoice->student_id !== $user->student->id) {
            abort(403, 'Unauthorized action.');
        }

        $reference = $request->query('reference') ?? $request->input('reference');

        if (!$reference) {
            return redirect()->route('invoices.show', $invoice)->with('error', 'No payment reference found. Please try again.');
        }

        // Prevent duplicate processing of the same reference
        if (Payment::where('reference_number', $reference)->exists()) {
            return redirect()->route('invoices.show', $invoice)->with('info', 'This payment has already been recorded.');
        }

        // Verify with Paystack API
        $response = Http::withToken(config('services.paystack.secret_key'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if (!$response->successful()) {
            Log::error('Paystack verification request failed', ['reference' => $reference, 'status' => $response->status()]);
            return redirect()->route('invoices.show', $invoice)->with('error', 'Could not reach payment gateway. Please contact support.');
        }

        $data = $response->json();

        if (!($data['status'] ?? false) || ($data['data']['status'] ?? '') !== 'success') {
            return redirect()->route('invoices.show', $invoice)->with('error', 'Payment was not successful. No charges have been made.');
        }

        // Amount from Paystack is in pesewas (GHS) — convert back to cedis
        $amountPaid = $data['data']['amount'] / 100;

        // Clamp to actual balance (safety net)
        $amountPaid = min($amountPaid, $invoice->balance);

        DB::transaction(function () use ($amountPaid, $invoice, $reference, $data) {
            $payment = Payment::create([
                'invoice_id'       => $invoice->id,
                'amount'           => $amountPaid,
                'payment_date'     => now(),
                'payment_method'   => 'paystack',
                'reference_number' => $reference,
                'recorded_by'      => Auth::id(),
                'notes'            => 'Paystack online payment. Channel: ' . ($data['data']['channel'] ?? 'online'),
                'status'           => 'completed',
            ]);

            Receipt::create([
                'payment_id'     => $payment->id,
                'receipt_number' => 'RCT-' . strtoupper(Str::random(8)),
                'issued_by'      => null,
            ]);

            $invoice->paid_amount += $amountPaid;
            $invoice->balance     -= $amountPaid;
            $invoice->status       = $invoice->balance <= 0 ? 'paid' : 'partial';
            $invoice->save();
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Payment of GHS ' . number_format($amountPaid, 2) . ' received successfully via Paystack!');
    }
}
