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
        Schema::create('target_kinerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('indikator_kinerja_id')->constrained('indikator_kinerja')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periode')->onDelete('cascade');
            $table->decimal('target', 10, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['user_id', 'indikator_kinerja_id', 'periode_id'], 'unique_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_kinerja');
    }
};
