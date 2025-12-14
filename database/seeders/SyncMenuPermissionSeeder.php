<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SyncMenuPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = Menu::whereNotNull('permission_name')->get();

        foreach ($menus as $menu) {
            Permission::firstOrCreate([
                'name' => $menu->permission_name,
                'guard_name' => 'api'
            ]);
        }
    }
}
