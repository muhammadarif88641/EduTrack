
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->unsignedInteger('week_number')->default(1);
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->unsignedInteger('week_number')->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('week_number');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('week_number');
        });
    }
};
