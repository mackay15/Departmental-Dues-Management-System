<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Programme;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function debtors(Request $request)
    {
        $query = Invoice::with(['student.programme', 'academicSession'])
            ->where('balance', '>', 0);
            
        if ($request->has('programme_id') && $request->programme_id != '') {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('programme_id', $request->programme_id);
            });
        }

        $debtors = $query->get();
        $programmes = Programme::all();

        if ($request->has('export') && $request->export == 'csv') {
            return $this->exportDebtorsCsv($debtors);
        }

        return view('reports.debtors', compact('debtors', 'programmes'));
    }

    public function revenue(Request $request)
    {
        $query = Payment::with(['student.programme', 'invoice.academicSession'])
            ->where('status', 'completed');

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        $payments = $query->get();

        if ($request->has('export') && $request->export == 'csv') {
            return $this->exportRevenueCsv($payments);
        }

        return view('reports.revenue', compact('payments'));
    }

    private function exportDebtorsCsv($debtors)
    {
        $filename = "debtors_report_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Index Number', 'Name', 'Programme', 'Session', 'Invoice Total', 'Paid', 'Balance'];

        $callback = function() use($debtors, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($debtors as $invoice) {
                $row = [
                    $invoice->student->index_number,
                    $invoice->student->first_name . ' ' . $invoice->student->last_name,
                    $invoice->student->programme->name ?? 'N/A',
                    $invoice->academicSession->name ?? 'N/A',
                    $invoice->total_amount,
                    $invoice->paid_amount,
                    $invoice->balance
                ];
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportRevenueCsv($payments)
    {
        $filename = "revenue_report_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Receipt No', 'Date', 'Index Number', 'Name', 'Session', 'Amount'];

        $callback = function() use($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payments as $payment) {
                $row = [
                    $payment->receipt_number ?? 'N/A',
                    $payment->payment_date,
                    $payment->student->index_number ?? 'N/A',
                    $payment->student->first_name . ' ' . $payment->student->last_name,
                    $payment->invoice->academicSession->name ?? 'N/A',
                    $payment->amount
                ];
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
