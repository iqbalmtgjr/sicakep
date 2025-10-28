<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('atasan_id')->nullable()->after('bidang_id')->constrained('users')->nullOnDelete();
            $table->string('level_jabatan')->nullable()->after('jabatan'); // kepala_dinas, sekretaris, kasubbag, kabid, kasubag_upt, kepala_upt, staff
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['atasan_id']);
            $table->dropColumn(['atasan_id', 'level_jabatan']);
        });
    }
};
