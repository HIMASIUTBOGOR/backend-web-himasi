<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Proker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akademik = Departemen::create([
            'id' => \Str::uuid(),
            'icon' => 'pepicons-print:book',
            'title' => 'Akademik & Keilmuan',
            'desc' => 'Fokus pada pengembangan akademik dan wawasan teknologi informasi mahasiswa.',
        ]);
       $media = Departemen::create([
            'id' => \Str::uuid(),
            'icon' => 'material-symbols-light:media-link-sharp',
            'title' => 'Media & Publikasi',
            'desc' => 'Mengelola informasi, media sosial, dan branding organisasi.',
        ]);
        $psdm = Departemen::create([
            'id' => \Str::uuid(),
            'icon' => 'streamline-freehand-color:human-resources-hierarchy',
            'title' => 'PSDM',
            'desc' => 'Pengembangan Sumber Daya Mahasiswa, kaderisasi, dan pelatihan soft skill.',
        ]);
        $acara = Departemen::create([
            'id' => \Str::uuid(),
            'icon' => 'ic:outline-emoji-events',
            'title' => 'Acara & Humas',
            'desc' => 'Menyelenggarakan event dan menjalin hubungan dengan pihak luar.',
        ]);
        $olahraga = Departemen::create([
            'icon' => 'fluent:sport-20-filled',
            'title' => 'Olahraga',
            'desc' => 'Menampung minat dan bakat mahasiswa di bidang olahraga.',
        ]);


        Proker::create([
            'departemen_id' => $akademik->id,
            'photo' => null,
            'title' => 'Belajar Bersama (Study Club)',
            'desc' => 'Kegiatan rutin untuk membahas materi perkuliahan dan belajar teknologi baru bersama-sama. Dipandu oleh mentor dari mahasiswa senior atau alumni.',
            'action_link' => null,
            'is_active' => true,
        ]);
        Proker::create([
            'departemen_id' => $akademik->id,
            'photo' => null,
            'title' => 'Webinar Teknologi',
            'desc' => 'Agenda berbagi wawasan teknologi terbaru bersama praktisi dan akademisi.',
            'action_link' => null,
            'is_active' => true,
        ]);


    }
}
