<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departemen::create([
            'icon' => 'smile',
            'title' => 'Akademik & Keilmuan',
            'desc' => 'Fokus pada pengembangan akademik dan wawasan teknologi informasi mahasiswa.',
        ]);
        Departemen::create([
            'icon' => 'smile',
            'title' => 'Media & Publikasi',
            'desc' => 'Mengelola informasi, media sosial, dan branding organisasi.',
        ]);
        Departemen::create([
            'icon' => 'smile',
            'title' => 'PSDM',
            'desc' => 'Pengembangan Sumber Daya Mahasiswa, kaderisasi, dan pelatihan soft skill.',
        ]);
        Departemen::create([
            'icon' => 'smile',
            'title' => 'Acara & Humas',
            'desc' => 'Menyelenggarakan event dan menjalin hubungan dengan pihak luar.',
        ]);
        Departemen::create([
            'icon' => 'smile',
            'title' => 'Olahraga',
            'desc' => 'Menampung minat dan bakat mahasiswa di bidang olahraga.',
        ]);
    }
}
