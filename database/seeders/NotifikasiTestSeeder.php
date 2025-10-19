<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user pertama untuk testing
        $user = User::first();

        if (!$user) {
            $this->command->error('Tidak ada user di database. Silakan buat user terlebih dahulu.');
            return;
        }

        // Hapus notifikasi penilaian lama jika ada
        Notifikasi::where('user_id', $user->id)
            ->where('tipe', 'penilaian')
            ->delete();

        $this->command->info('Membuat notifikasi test untuk user: ' . $user->name);

        // Test Case 1: Penilaian Sangat Baik (91-100)
        Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Penilaian Kinerja Anda Telah Tersedia!',
            'pesan' => json_encode([
                'nilai' => 95.5,
                'persentase' => 95.5,
                'periode' => 'Triwulan 1 2025',
                'predikat' => 'Sangat Baik',
                'catatan' => 'Kinerja Anda sangat memuaskan! Terus pertahankan dan tingkatkan prestasi Anda. Anda menunjukkan dedikasi dan komitmen yang tinggi.',
                'tanggal' => now()->format('d F Y'),
                'penilai' => 'Kepala Dinas',
            ]),
            'tipe' => 'penilaian',
            'is_read' => false,
        ]);

        $this->command->info('✓ Notifikasi Sangat Baik (95.5) berhasil dibuat');

        // Test Case 2: Penilaian Baik (76-90) - Buat sebagai sudah dibaca
        Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Penilaian Kinerja Anda Telah Tersedia!',
            'pesan' => json_encode([
                'nilai' => 85.0,
                'persentase' => 85.0,
                'periode' => 'Triwulan 4 2024',
                'predikat' => 'Baik',
                'catatan' => 'Pekerjaan Anda sudah baik, namun masih ada beberapa aspek yang perlu ditingkatkan terutama dalam hal inovasi.',
                'tanggal' => now()->subDays(10)->format('d F Y'),
                'penilai' => 'Kepala Bidang',
            ]),
            'tipe' => 'penilaian',
            'is_read' => true,
            'read_at' => now()->subDays(9),
        ]);

        $this->command->info('✓ Notifikasi Baik (85.0) berhasil dibuat (sudah dibaca)');

        // Test Case 3: Penilaian Cukup (61-75)
        Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Penilaian Kinerja Anda Telah Tersedia!',
            'pesan' => json_encode([
                'nilai' => 70.0,
                'persentase' => 70.0,
                'periode' => 'Triwulan 3 2024',
                'predikat' => 'Cukup',
                'catatan' => 'Kinerja Anda cukup baik namun perlu lebih konsisten. Fokus pada peningkatan kualitas kerja dan ketepatan waktu.',
                'tanggal' => now()->subDays(30)->format('d F Y'),
                'penilai' => 'Kepala Seksi',
            ]),
            'tipe' => 'penilaian',
            'is_read' => true,
            'read_at' => now()->subDays(28),
        ]);

        $this->command->info('✓ Notifikasi Cukup (70.0) berhasil dibuat (sudah dibaca)');

        // Test Case 4: Penilaian Kurang (50-60)
        Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Penilaian Kinerja Anda Telah Tersedia!',
            'pesan' => json_encode([
                'nilai' => 58.0,
                'persentase' => 58.0,
                'periode' => 'Triwulan 2 2024',
                'predikat' => 'Kurang',
                'catatan' => 'Kinerja Anda perlu ditingkatkan secara signifikan. Kami akan memberikan pendampingan dan bimbingan untuk membantu Anda mencapai standar yang diharapkan.',
                'tanggal' => now()->subDays(60)->format('d F Y'),
                'penilai' => 'Kepala Dinas',
            ]),
            'tipe' => 'penilaian',
            'is_read' => true,
            'read_at' => now()->subDays(55),
        ]);

        $this->command->info('✓ Notifikasi Kurang (58.0) berhasil dibuat (sudah dibaca)');

        $this->command->newLine();
        $this->command->info('========================================');
        $this->command->info('Seeder berhasil dijalankan!');
        $this->command->info('Total notifikasi dibuat: 4');
        $this->command->info('Notifikasi belum dibaca: 1 (Sangat Baik)');
        $this->command->info('Notifikasi sudah dibaca: 3');
        $this->command->info('========================================');
        $this->command->newLine();
        $this->command->warn('CATATAN: Notifikasi "Sangat Baik" akan muncul saat user membuka halaman Realisasi Kinerja');
    }
}
