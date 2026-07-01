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
    Route::get('payments/{payment}/receipt', [ReceiptController::class, 'print'])->name('receipts.print');

    // Student Self-Service Payment Routes
    Route::get('invoices/{invoice}/pay', [\App\Http\Controllers\StudentPaymentController::class, 'showPayForm'])->name('student.payments.pay');
    Route::post('invoices/{invoice}/pay', [\App\Http\Controllers\StudentPaymentController::class, 'processPayment'])->name('student.payments.process');

    // Staff Only Routes (Admins, HODs, Finance, Auditors)
    Route::middleware('non_student')->group(function () {
        // Student registration, editing, importing and list are restricted to HOD only
        Route::middleware('role:HOD')->group(function () {
            Route::get('students', [StudentController::class, 'index'])->name('students.index');
            Route::get('students/import', [StudentController::class, 'showImportForm'])->name('students.import');
            Route::post('students/import', [StudentController::class, 'import'])->name('students.import.store');
            Route::get('students/import-template', [StudentController::class, 'downloadTemplate'])->name('students.import.template');

            Route::get('students/create', [StudentController::class, 'create'])->name('students.create');
            Route::post('students', [StudentController::class, 'store'])->name('students.store');
            Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
            Route::match(['put', 'patch'], 'students/{student}', [StudentController::class, 'update'])->name('students.update');
            Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        });

        Route::resource('academic-sessions', AcademicSessionController::class);
        Route::resource('dues', DueController::class)->only(['index']);

        // HOD-only infrastructure management routes
        Route::middleware('role:HOD')->group(function () {
            Route::resource('programmes', ProgrammeController::class);
            Route::resource('academic-levels', AcademicLevelController::class);
            Route::resource('dues', DueController::class)->except(['index', 'show']);

            // Invoices Generation - HOD only
            Route::get('invoices/generate', [InvoiceController::class, 'createGenerationForm'])->name('invoices.generate');
            Route::post('invoices/generate', [InvoiceController::class, 'generate'])->name('invoices.store_generation');
        });
        
        // Payments Routes
        Route::get('payments/record', [PaymentController::class, 'record'])->name('payments.record');
        Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
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
        // Auditor-only routes
        Route::middleware('role:Auditor|HOD')->group(function () {
            Route::get('auditor/logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('auditor.logs');
            Route::get('auditor/logs/print', [\App\Http\Controllers\ActivityLogController::class, 'print'])->name('auditor.logs.print');
        });
    });

    // Wildcard routes placed at the end to prevent capturing specific routes (like 'students/create' and 'invoices/generate')
    Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
});

require __DIR__.'/auth.php';
