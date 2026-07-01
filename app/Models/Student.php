<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\LogsActivity;

class Student extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'index_number',
        'first_name',
        'last_name',
        'other_names',
        'email',
        'phone',
        'photo_path',
        'programme_id',
        'current_level_id',
        'status',
        'user_id'
    ];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function currentLevel(): BelongsTo
    {
        return $this->belongsTo(AcademicLevel::class, 'current_level_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function academicRecords(): HasMany
    {
        return $this->hasMany(StudentAcademicRecord::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Invoice::class);
    }
}
