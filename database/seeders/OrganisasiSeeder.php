<?php
// database/seeders/OrganisasiSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bidang;
use Illuminate\Database\Seeder;

class OrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kepala Dinas
        $kepalaDinas = User::create([
            'name' => 'Dr. Budi Santoso, M.Si',
            'nip' => '196501011990031001',
            'email' => 'kepala.dinas@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'kepala_dinas',
            'jabatan' => 'Kepala Dinas Perindustrian, Perdagangan, Koperasi, Usaha Kecil dan Menengah',
            'pangkat_golongan' => 'Pembina Utama Madya (IV/d)',
        ]);

        // 2. Sekretaris
        $sekretaris = User::create([
            'name' => 'Ir. Siti Aminah, M.M',
            'nip' => '196801011991032001',
            'email' => 'sekretaris@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'sekretaris',
            'jabatan' => 'Sekretaris',
            'pangkat_golongan' => 'Pembina Tk.I (IV/b)',
            'atasan_id' => $kepalaDinas->id,
        ]);

        // 3. Bidang
        $bidangPerindustrian = Bidang::create([
            'nama_bidang' => 'Bidang Perindustrian',
            'kode_bidang' => 'IND',
        ]);

        $bidangPerdagangan = Bidang::create([
            'nama_bidang' => 'Bidang Perdagangan',
            'kode_bidang' => 'DAG',
        ]);

        $bidangPasar = Bidang::create([
            'nama_bidang' => 'Bidang Pasar',
            'kode_bidang' => 'PSR',
        ]);

        $bidangKoperasi = Bidang::create([
            'nama_bidang' => 'Bidang Koperasi, UMKM',
            'kode_bidang' => 'KOP',
        ]);

        // 4. Kasubbag di Sekretariat
        $kasubbagKeuangan = User::create([
            'name' => 'Ahmad Rifai, S.E',
            'nip' => '197001011992031002',
            'email' => 'kasubbag.keuangan@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'kasubbag',
            'jabatan' => 'Kasubbag Keuangan dan Program',
            'pangkat_golongan' => 'Penata Tk.I (III/d)',
            'atasan_id' => $sekretaris->id,
        ]);

        $kasubbagUmum = User::create([
            'name' => 'Dewi Kartika, S.Sos',
            'nip' => '197101011993032001',
            'email' => 'kasubbag.umum@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'kasubbag',
            'jabatan' => 'Kasubbag Aparatur dan Umum',
            'pangkat_golongan' => 'Penata Tk.I (III/d)',
            'atasan_id' => $sekretaris->id,
        ]);

        // 5. Kepala Bidang
        $kabidIndustri = User::create([
            'name' => 'Ir. Hendra Wijaya',
            'nip' => '197201011994031001',
            'email' => 'kabid.industri@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'kabid',
            'jabatan' => 'Kepala Bidang Perindustrian',
            'pangkat_golongan' => 'Pembina (IV/a)',
            'bidang_id' => $bidangPerindustrian->id,
            'atasan_id' => $kepalaDinas->id,
        ]);

        $kabidDagang = User::create([
            'name' => 'Dra. Ratna Sari',
            'nip' => '197301011995032001',
            'email' => 'kabid.dagang@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'kabid',
            'jabatan' => 'Kepala Bidang Perdagangan',
            'pangkat_golongan' => 'Pembina (IV/a)',
            'bidang_id' => $bidangPerdagangan->id,
            'atasan_id' => $kepalaDinas->id,
        ]);

        $kabidPasar = User::create([
            'name' => 'Budi Hartono, S.E',
            'nip' => '197401011996031001',
            'email' => 'kabid.pasar@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'kabid',
            'jabatan' => 'Kepala Bidang Pasar',
            'pangkat_golongan' => 'Pembina (IV/a)',
            'bidang_id' => $bidangPasar->id,
            'atasan_id' => $kepalaDinas->id,
        ]);

        $kabidKoperasi = User::create([
            'name' => 'Sri Wahyuni, S.E., M.M',
            'nip' => '197501011997032001',
            'email' => 'kabid.koperasi@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'kabid',
            'jabatan' => 'Kepala Bidang Koperasi, UMKM',
            'pangkat_golongan' => 'Pembina (IV/a)',
            'bidang_id' => $bidangKoperasi->id,
            'atasan_id' => $kepalaDinas->id,
        ]);

        // 6. Staff di masing-masing bidang
        User::create([
            'name' => 'Andi Prasetyo',
            'nip' => '198001012005011001',
            'email' => 'andi.prasetyo@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'level_jabatan' => 'staff',
            'jabatan' => 'Staff Bidang Perindustrian',
            'pangkat_golongan' => 'Penata Muda (III/a)',
            'bidang_id' => $bidangPerindustrian->id,
            'atasan_id' => $kabidIndustri->id,
        ]);

        User::create([
            'name' => 'Rini Anggraini',
            'nip' => '198101012006012001',
            'email' => 'rini.anggraini@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'level_jabatan' => 'staff',
            'jabatan' => 'Staff Bidang Perdagangan',
            'pangkat_golongan' => 'Penata Muda (III/a)',
            'bidang_id' => $bidangPerdagangan->id,
            'atasan_id' => $kabidDagang->id,
        ]);

        // 7. UPT Metrologi Legal
        $kepalaUPT = User::create([
            'name' => 'Drs. Agus Setiawan',
            'nip' => '197601012000031001',
            'email' => 'kepala.upt@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'kepala_upt',
            'jabatan' => 'Kepala UPT Metrologi Legal',
            'pangkat_golongan' => 'Pembina (IV/a)',
            'atasan_id' => $kepalaDinas->id,
        ]);

        User::create([
            'name' => 'Lina Marlina, S.E',
            'nip' => '198201012007012001',
            'email' => 'kasubag.upt@sintang.go.id',
            'password' => bcrypt('password'),
            'role' => 'atasan',
            'level_jabatan' => 'kasubag_upt',
            'jabatan' => 'Kasubbag Tata Usaha UPT Metrologi Legal',
            'pangkat_golongan' => 'Penata (III/c)',
            'atasan_id' => $kepalaUPT->id,
        ]);

        $this->command->info('Struktur organisasi berhasil dibuat!');
    }
}
