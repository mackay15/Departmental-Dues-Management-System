<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\AcademicLevelController;
use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DueController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}/receipt', [ReceiptController::class, 'download'])->name('receipts.download');

    // Student Self-Service Payment Routes
    Route::get('invoices/{invoice}/pay', [\App\Http\Controllers\StudentPaymentController::class, 'showPayForm'])->name('student.payments.pay');
    Route::post('invoices/{invoice}/pay', [\App\Http\Controllers\StudentPaymentController::class, 'processPayment'])->name('student.payments.process');

    // Staff Only Routes (Admins, HODs, Finance, Auditors)
    Route::middleware('non_student')->group(function () {
        // Students Import Routes
        Route::get('students/import', [StudentController::class, 'showImportForm'])->name('students.import');
        Route::post('students/import', [StudentController::class, 'import'])->name('students.import.store');
        Route::get('students/import-template', [StudentController::class, 'downloadTemplate'])->name('students.import.template');

        Route::resource('students', StudentController::class)->except(['show']);
        Route::resource('academic-sessions', AcademicSessionController::class);
        Route::resource('dues', DueController::class);

        // HOD-only infrastructure management routes
        Route::middleware('role:HOD')->group(function () {
            Route::resource('programmes', ProgrammeController::class);
            Route::resource('academic-levels', AcademicLevelController::class);
        });
        
        // Invoices Routes
        Route::get('invoices/generate', [InvoiceController::class, 'createGenerationForm'])->name('invoices.generate');
        Route::post('invoices/generate', [InvoiceController::class, 'generate'])->name('invoices.store_generation');
        
        // Payments Routes
        Route::get('invoices/{invoice}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
        
        // Promotions Routes
        Route::get('promotions', [App\Http\Controllers\StudentPromotionController::class, 'index'])->name('promotions.index');
        Route::get('promotions/preview', [App\Http\Controllers\StudentPromotionController::class, 'preview'])->name('promotions.preview');
        Route::post('promotions/promote', [App\Http\Controllers\StudentPromotionController::class, 'promote'])->name('promotions.promote');
        
        // Reports Routes
        Route::get('reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/debtors', [App\Http\Controllers\ReportController::class, 'debtors'])->name('reports.debtors');
        Route::get('reports/revenue', [App\Http\Controllers\ReportController::class, 'revenue'])->name('reports.revenue');

        // User Management Routes (HOD only)
        Route::middleware('role:HOD')->group(function () {
            Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
            Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        });
    });

    // Wildcard routes placed at the end to prevent capturing specific routes (like 'students/create' and 'invoices/generate')
    Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
});

require __DIR__.'/auth.php';
