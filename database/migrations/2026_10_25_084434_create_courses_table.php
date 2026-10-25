
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            // Lecturer who created the course
            $table->foreignId('lecturer_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Course information
            $table->string('course_code')->unique();
            $table->string('course_name');
            $table->text('description')->nullable();

            // Admin approval status
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
