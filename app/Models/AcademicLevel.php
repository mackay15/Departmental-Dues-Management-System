<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // e.g. Level 100
        'numeric_value', // e.g. 100
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'current_level_id');
    }

    public function academicRecords(): HasMany
    {
        return $this->hasMany(StudentAcademicRecord::class, 'academic_level_id');
    }
}
