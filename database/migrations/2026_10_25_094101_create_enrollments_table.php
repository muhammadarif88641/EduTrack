<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            // Student requesting enrollment
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Course the student wants to join
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->cascadeOnDelete();

            // Admin approval status
            $table->string('status')->default('pending');

            // Prevent duplicate enrollment requests
            $table->unique(['student_id', 'course_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
