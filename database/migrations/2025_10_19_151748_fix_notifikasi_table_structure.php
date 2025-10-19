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
        // Cek apakah tabel sudah ada
        if (Schema::hasTable('notifikasi')) {
            // Jika tabel sudah ada, tambahkan kolom yang kurang
            Schema::table('notifikasi', function (Blueprint $table) {
                // Cek dan tambahkan kolom user_id jika belum ada
                if (!Schema::hasColumn('notifikasi', 'user_id')) {
                    $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
                }

                // Cek dan tambahkan kolom judul jika belum ada
                if (!Schema::hasColumn('notifikasi', 'judul')) {
                    $table->string('judul')->after('user_id');
                }

                // Cek dan tambahkan kolom pesan jika belum ada
                if (!Schema::hasColumn('notifikasi', 'pesan')) {
                    $table->text('pesan')->after('judul');
                }

                // Cek dan tambahkan kolom tipe jika belum ada
                if (!Schema::hasColumn('notifikasi', 'tipe')) {
                    $table->string('tipe')->after('pesan')->default('info');
                }

                // Cek dan tambahkan kolom is_read jika belum ada
                if (!Schema::hasColumn('notifikasi', 'is_read')) {
                    $table->boolean('is_read')->after('tipe')->default(false);
                }

                // Cek dan tambahkan kolom read_at jika belum ada
                if (!Schema::hasColumn('notifikasi', 'read_at')) {
                    $table->timestamp('read_at')->after('is_read')->nullable();
                }
            });
        } else {
            // Jika tabel belum ada, buat baru
            Schema::create('notifikasi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('judul');
                $table->text('pesan');
                $table->string('tipe')->default('info'); // penilaian, info, warning, etc
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                // Indexes untuk performance
                $table->index(['user_id', 'is_read', 'tipe']);
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom yang ditambahkan (jika rollback)
        if (Schema::hasTable('notifikasi')) {
            Schema::table('notifikasi', function (Blueprint $table) {
                if (Schema::hasColumn('notifikasi', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }
    }
};
