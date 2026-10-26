<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //Run the migrations.
    
public function up(): void
{
    Schema::create('materials', function (Blueprint $table) {
        $table->id();

        // Course that owns this material
        $table->foreignId('course_id')
            ->constrained('courses')
            ->cascadeOnDelete();

        // Material information
        $table->string('title');
        $table->text('description')->nullable();

        // Uploaded file information
        $table->string('file_path');
        $table->string('original_filename')->nullable();

        $table->timestamps();
    });
}


    //Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
