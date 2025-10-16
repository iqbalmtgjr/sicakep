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
        Schema::create('penilaian_kinerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periode')->onDelete('cascade');
            $table->decimal('total_realisasi', 10, 2)->default(0);
            $table->decimal('total_target', 10, 2)->default(0);
            $table->decimal('persentase_capaian', 5, 2)->default(0);
            $table->decimal('nilai_kinerja', 5, 2)->default(0);
            $table->string('predikat')->nullable(); // Sangat Baik, Baik, Cukup, Kurang
            $table->text('catatan')->nullable();
            $table->foreignId('dinilai_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('tanggal_penilaian')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'periode_id'], 'unique_penilaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_kinerja');
    }
};
