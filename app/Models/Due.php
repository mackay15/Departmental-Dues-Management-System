<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\LogsActivity;

class Due extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'category_name',
        'amount',
        'academic_session_id',
        'programme_id',
        'academic_level_id',
        'due_date',
        'description',
    ];

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function academicLevel(): BelongsTo
    {
        return $this->belongsTo(AcademicLevel::class);
    }
}
