<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Due;
use App\Models\Student;
use App\Models\AcademicSession;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['student', 'academicSession']);
        
        $user = auth()->user();
        if ($user && $user->hasRole('Student') && $user->student) {
            $query->where('student_id', $user->student->id);
        }
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('student', function($q) use ($search) {
                      $q->where('index_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
        }

        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('status', $request->get('status'));
        }

        $invoices = $query->latest()->paginate(20);
        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('Student') && $user->student && $invoice->student_id !== $user->student->id) {
            abort(403, 'Unauthorized action.');
        }

        $invoice->load(['student.programme', 'academicSession', 'items.due', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function createGenerationForm()
    {
        $sessions = AcademicSession::all();
        $programmes = Programme::all();
        return view('invoices.generate', compact('sessions', 'programmes'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'programme_id' => 'nullable|exists:programmes,id',
        ]);

        $sessionId = $validated['academic_session_id'];
        $programmeId = $validated['programme_id'];

        // Get applicable dues for this session
        $duesQuery = Due::where('academic_session_id', $sessionId);
        if ($programmeId) {
            $duesQuery->where(function($q) use ($programmeId) {
                $q->whereNull('programme_id')
                  ->orWhere('programme_id', $programmeId);
            });
        }
        $applicableDues = $duesQuery->get();

        if ($applicableDues->isEmpty()) {
            return back()->with('error', 'No dues found for the selected criteria. Please create dues first.');
        }

        // Get active students
        $studentsQuery = Student::where('status', 'active');
        if ($programmeId) {
            $studentsQuery->where('programme_id', $programmeId);
        }
        $students = $studentsQuery->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No active students found matching the criteria.');
        }

        $generatedCount = 0;

        DB::transaction(function () use ($students, $applicableDues, $sessionId, &$generatedCount) {
            foreach ($students as $student) {
                // Filter dues specific to this student's level or null
                $studentDues = $applicableDues->filter(function($due) use ($student) {
                    return is_null($due->academic_level_id) || $due->academic_level_id == $student->current_level_id;
                });

                if ($studentDues->isEmpty()) continue;

                // Check if invoice already exists for this student in this session
                $existingInvoice = Invoice::where('student_id', $student->id)
                                          ->where('academic_session_id', $sessionId)
                                          ->first();
                if ($existingInvoice) continue; // Skip to avoid double billing

                $totalAmount = $studentDues->sum('amount');
                $dueDate = $studentDues->max('due_date') ?? now()->addDays(30);

                $invoice = Invoice::create([
                    'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                    'student_id' => $student->id,
                    'academic_session_id' => $sessionId,
                    'total_amount' => $totalAmount,
                    'paid_amount' => 0,
                    'balance' => $totalAmount,
                    'status' => 'unpaid',
                    'due_date' => $dueDate,
                ]);

                foreach ($studentDues as $due) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'due_id' => $due->id,
                        'amount' => $due->amount,
                    ]);
                }

                $generatedCount++;
            }
        });

        if ($generatedCount === 0) {
            return redirect()->route('invoices.index')->with('info', 'No new invoices were generated. Students may have already been billed for this session.');
        }

        return redirect()->route('invoices.index')->with('success', "Successfully generated {$generatedCount} invoices.");
    }
}
