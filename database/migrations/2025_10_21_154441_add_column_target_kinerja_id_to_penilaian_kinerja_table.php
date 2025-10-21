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
        Schema::table('penilaian_kinerja', function (Blueprint $table) {
            $table->foreignId('target_kinerja_id')->constrained('target_kinerja')->onDelete('cascade')->after('periode_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_kinerja', function (Blueprint $table) {
            $table->dropColumn('target_kinerja_id');
        });
    }
};
