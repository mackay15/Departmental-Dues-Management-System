<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('students', 'student_number')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('student_number');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('students', 'student_number')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('student_number')->unique()->after('id');
            });
        }
    }
};
