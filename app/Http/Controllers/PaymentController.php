<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.student', 'recordedBy']);

        $user = auth()->user();
        if ($user && $user->hasRole('Student') && $user->student) {
            $query->whereHas('invoice', function ($q) use ($user) {
                $q->where('student_id', $user->student->id);
            });
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('invoice.student', function($q) use ($search) {
                      $q->where('index_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
        }

        $payments = $query->latest('payment_date')->paginate(20);
        return view('payments.index', compact('payments'));
    }

    public function create(Invoice $invoice)
    {
        if ($invoice->balance <= 0) {
            return redirect()->route('invoices.show', $invoice)->with('info', 'This invoice is already fully paid.');
        }

        return view('payments.create', compact('invoice'));
    }

    public function store(Request $request, Invoice $invoice)
    {
        if ($invoice->balance <= 0) {
            return redirect()->route('invoices.show', $invoice)->with('error', 'Invoice is already fully paid.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $invoice->balance,
            'payment_method' => 'required|string|in:cash,bank_transfer,momo,card',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            
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

        $notes = $validated['notes'] ?? '';
        if ($validated['payment_method'] === 'momo') {
            $momoInfo = 'Momo via ' . strtoupper($validated['momo_provider']) . ' (' . $validated['momo_number'] . ')';
            $notes = $notes ? $notes . ' | ' . $momoInfo : $momoInfo;
        } elseif ($validated['payment_method'] === 'card') {
            $maskedCard = 'XXXX-XXXX-XXXX-' . substr($validated['card_number'], -4);
            $cardInfo = 'Card payment via ' . $maskedCard;
            $notes = $notes ? $notes . ' | ' . $cardInfo : $cardInfo;
        }

        DB::transaction(function () use ($validated, $invoice, $notes) {
            $refPrefix = strtoupper($validated['payment_method']);
            $refNumber = $validated['reference_number'] ?? ($refPrefix . '-' . strtoupper(Str::random(10)));

            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $refNumber,
                'recorded_by' => Auth::id(),
                'notes' => $notes,
            ]);

            // Generate a Receipt
            Receipt::create([
                'payment_id' => $payment->id,
                'receipt_number' => 'RCT-' . strtoupper(Str::random(8)),
                'issued_by' => Auth::id(),
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

        return redirect()->route('invoices.show', $invoice)->with('success', 'Payment recorded and receipt generated successfully.');
    }
}
