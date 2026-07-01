<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Traits\LogsActivity;

class Payment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'recorded_by',
        'notes',
        'status',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            Student::class,
            Invoice::class,
            'id',          // Foreign key on invoices table (invoice.id)
            'id',          // Foreign key on students table (student.id)
            'invoice_id',  // Local key on payments table
            'student_id'   // Local key on invoices table
        );
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }
}
