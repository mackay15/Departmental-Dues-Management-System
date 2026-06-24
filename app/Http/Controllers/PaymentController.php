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
                      $q->where('student_number', 'like', "%{$search}%")
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
            'payment_method' => 'required|string|in:cash,bank_transfer,momo',
            'reference_number' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $invoice) {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? 'REF-' . strtoupper(Str::random(6)),
                'recorded_by' => Auth::id(),
                'notes' => $validated['notes'],
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
