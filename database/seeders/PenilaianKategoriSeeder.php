<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PenilaianKategori;

class PenilaianKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'kategori' => 'Sangat Baik',
                'badge' => 'success',
                'icon' => 'ki-medal-star',
                'pesan' => 'Anda menunjukkan kinerja yang sangat tinggi dan konsisten melampaui target yang ditetapkan. Hasil kerja sangat berkualitas, efisien, serta memberikan kontribusi signifikan terhadap pencapaian tujuan organisasi.',
                'min_nilai' => 91,
                'max_nilai' => 100,
            ],
            [
                'kategori' => 'Baik',
                'badge' => 'primary',
                'icon' => 'ki-like',
                'pesan' => 'Anda mampu melaksanakan tugas dan tanggung jawab dengan baik serta menunjukkan tingkat profesionalitas yang memadai. Hasil kerja berkualitas, sesuai dengan target dan harapan organisasi.',
                'min_nilai' => 76,
                'max_nilai' => 90,
            ],
            [
                'kategori' => 'Cukup',
                'badge' => 'warning',
                'icon' => 'ki-information',
                'pesan' => 'Anda telah melaksanakan tugas dengan hasil yang cukup baik meskipun belum konsisten. Kinerja memenuhi sebagian besar target, namun masih ada ruang untuk peningkatan terutama pada aspek efektivitas, inovasi, dan tanggung jawab kerja.',
                'min_nilai' => 61,
                'max_nilai' => 75,
            ],
            [
                'kategori' => 'Kurang',
                'badge' => 'danger',
                'icon' => 'ki-information-2',
                'pesan' => 'Anda menunjukkan hasil kerja yang belum memenuhi standar yang ditetapkan. Masih terdapat banyak aspek yang perlu diperbaiki, baik dalam hal ketepatan waktu, kualitas hasil, maupun inisiatif kerja.',
                'min_nilai' => 0,
                'max_nilai' => 60,
            ],
        ];

        foreach ($kategoris as $kategori) {
            PenilaianKategori::create($kategori);
        }
    }
}
