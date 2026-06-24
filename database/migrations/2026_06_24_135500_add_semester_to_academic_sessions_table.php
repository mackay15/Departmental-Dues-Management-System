<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('academic_sessions', 'semester')) {
            Schema::table('academic_sessions', function (Blueprint $table) {
                $table->string('semester')->default('1')->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('academic_sessions', 'semester')) {
            Schema::table('academic_sessions', function (Blueprint $table) {
                $table->dropColumn('semester');
            });
        }
    }
};
