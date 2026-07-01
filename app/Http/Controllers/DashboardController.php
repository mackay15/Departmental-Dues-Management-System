<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Student')) {
            return $this->studentDashboard($user);
        }

        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        $totalStudents = Student::where('status', 'active')->count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $outstandingDues = Invoice::whereIn('status', ['unpaid', 'partial'])->sum('balance');
        $recentPayments = Payment::with(['student', 'invoice'])->latest()->take(5)->get();

        // Revenue over the last 6 months for Chart.js
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sum = Payment::where('status', 'completed')
                ->whereMonth('payment_date', $month->month)
                ->whereYear('payment_date', $month->year)
                ->sum('amount');
            
            $monthlyRevenue['labels'][] = $month->format('M Y');
            $monthlyRevenue['data'][] = $sum;
        }

        return view('dashboard.admin', compact(
            'totalStudents', 'totalRevenue', 'outstandingDues', 'recentPayments', 'monthlyRevenue'
        ));
    }

    private function studentDashboard($user)
    {
        $student = $user->student;
        
        if (!$student) {
            $totalOutstanding = 0.00;
            $recentInvoices = collect();
            $recentPayments = collect();
        } else {
            $totalOutstanding = Invoice::where('student_id', $student->id)
                                       ->whereIn('status', ['unpaid', 'partial'])
                                       ->sum('balance');
                                       
            $recentInvoices = Invoice::where('student_id', $student->id)->latest()->take(5)->get();
            $recentPayments = $student->payments()->with('receipt')->latest()->take(5)->get();
        }

        return view('dashboard.student', compact(
            'student', 'totalOutstanding', 'recentInvoices', 'recentPayments'
        ));
    }
}
