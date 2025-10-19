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
        Schema::table('indikator_kinerja', function (Blueprint $table) {
            $table->string('sasaran_program', 255)->after('nama_indikator');
            $table->text('indikator_program')->after('sasaran_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikator_kinerja', function (Blueprint $table) {
            $table->dropColumn(['sasaran_program', 'indikator_program']);
        });
    }
};
