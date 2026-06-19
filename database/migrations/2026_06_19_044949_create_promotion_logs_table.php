<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->foreignId('promoted_by')->constrained('users')->onDelete('cascade');
            $table->string('description');
            $table->json('details'); // stores list of student IDs, previous levels, and new levels
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_logs');
    }
};
