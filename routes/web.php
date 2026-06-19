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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('students', StudentController::class);
    Route::resource('programmes', ProgrammeController::class);
    Route::resource('academic-levels', AcademicLevelController::class);
    Route::resource('academic-sessions', AcademicSessionController::class);
    Route::resource('dues', DueController::class);
    
    // Invoices Routes
    Route::get('invoices/generate', [InvoiceController::class, 'createGenerationForm'])->name('invoices.generate');
    Route::post('invoices/generate', [InvoiceController::class, 'generate'])->name('invoices.store_generation');
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    
    // Payments Routes
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('invoices/{invoice}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
    
    // Receipts Route
    Route::get('payments/{payment}/receipt', [ReceiptController::class, 'download'])->name('receipts.download');
    
    // Promotions Routes
    Route::get('promotions', [App\Http\Controllers\StudentPromotionController::class, 'index'])->name('promotions.index');
    Route::get('promotions/preview', [App\Http\Controllers\StudentPromotionController::class, 'preview'])->name('promotions.preview');
    Route::post('promotions/promote', [App\Http\Controllers\StudentPromotionController::class, 'promote'])->name('promotions.promote');
    
    // Reports Routes
    Route::get('reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/debtors', [App\Http\Controllers\ReportController::class, 'debtors'])->name('reports.debtors');
    Route::get('reports/revenue', [App\Http\Controllers\ReportController::class, 'revenue'])->name('reports.revenue');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
