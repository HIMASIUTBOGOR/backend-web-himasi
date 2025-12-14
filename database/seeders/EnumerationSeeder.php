<?php

namespace Database\Seeders;

use App\Models\Enumeration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Validation\Rules\Enum;

class EnumerationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Enumeration::create([
            'key' => 'article_category',
            'value' => 'Akademik',
            // 'desc' => 'User with full access to the system',
        ]);

        Enumeration::create([
            'key' => 'article_category',
            'value' => 'Event',
            // 'desc' => 'User with full access to the system',
        ]);
        
        Enumeration::create([
            'key' => 'article_category',
            'value' => 'Inovasi',
            // 'desc' => 'User with full access to the system',
        ]);
    }
}
