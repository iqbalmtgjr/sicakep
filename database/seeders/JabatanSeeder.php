<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            ['nama_jabatan' => 'Kepala Dinas', 'kode_jabatan' => 'KD', 'deskripsi' => 'Kepala Dinas Perindustrian, Perdagangan, Koperasi, Usaha Kecil dan Menengah'],
            ['nama_jabatan' => 'Sekretaris', 'kode_jabatan' => 'SEK', 'deskripsi' => 'Sekretaris Dinas'],
            ['nama_jabatan' => 'Kasubbag Keuangan dan Program', 'kode_jabatan' => 'KSUB_KP', 'deskripsi' => 'Kasubbag Keuangan dan Program'],
            ['nama_jabatan' => 'Kasubbag Aparatur dan Umum', 'kode_jabatan' => 'KSUB_AU', 'deskripsi' => 'Kasubbag Aparatur dan Umum'],
            ['nama_jabatan' => 'Kepala Bidang Perindustrian', 'kode_jabatan' => 'KBID_IND', 'deskripsi' => 'Kepala Bidang Perindustrian'],
            ['nama_jabatan' => 'Kepala Bidang Perdagangan', 'kode_jabatan' => 'KBID_DAG', 'deskripsi' => 'Kepala Bidang Perdagangan'],
            ['nama_jabatan' => 'Kepala Bidang Pasar', 'kode_jabatan' => 'KBID_PSR', 'deskripsi' => 'Kepala Bidang Pasar'],
            ['nama_jabatan' => 'Kepala Bidang Koperasi, UMKM', 'kode_jabatan' => 'KBID_KOP', 'deskripsi' => 'Kepala Bidang Koperasi, UMKM'],
            ['nama_jabatan' => 'Staff Bidang Perindustrian', 'kode_jabatan' => 'STF_IND', 'deskripsi' => 'Staff/Pelaksana Bidang Perindustrian'],
            ['nama_jabatan' => 'Staff Bidang Perdagangan', 'kode_jabatan' => 'STF_DAG', 'deskripsi' => 'Staff/Pelaksana Bidang Perdagangan'],
            ['nama_jabatan' => 'Kepala UPT Metrologi Legal', 'kode_jabatan' => 'KUPT_ML', 'deskripsi' => 'Kepala UPT Metrologi Legal'],
            ['nama_jabatan' => 'Kasubbag Tata Usaha UPT', 'kode_jabatan' => 'KSUB_UPT', 'deskripsi' => 'Kasubbag Tata Usaha UPT Metrologi Legal'],
        ];

        foreach ($jabatans as $jabatan) {
            \App\Models\Jabatan::create($jabatan);
        }

        $this->command->info('Jabatan berhasil dibuat!');
    }
}
