<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dashboard = Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Dashboard',
            'slug' => 'dashboard',
            'permission_name' => 'menu.dashboard',
            'order' => 1
        ]);

        $users = Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Users',
            'slug' => 'users',
            'permission_name' => 'menu.users',
            'order' => 2
        ]);

        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'User List',
            'slug' => 'users.list',
            'permission_name' => 'menu.users.list',
            'parent_id' => $users->id,
            'order' => 1
        ]);

        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Create User',
            'slug' => 'users.create',
            'permission_name' => 'menu.users.create',
            'parent_id' => $users->id,
            'order' => 2
        ]);
    }

}
