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
            'url' => '/dashboard',
            'permission_name' => 'menu.dashboard',
            'order' => 1,
            'icon' => 'material-symbols:dashboard'
        ]);

        $master = Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Master Data',
            'url' => '#',
            'permission_name' => 'menu.master',
            'order' => 2,
            'icon' => 'eos-icons:master-outlined'
        ]);
        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Enumeration',
            'url' => '/master/enumeration',
            'parent_id' => $master->id,
            'permission_name' => 'menu.master.enumeration',
            'order' => 1
        ]);
        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Department',
            'url' => '/master/department',
            'parent_id' => $master->id,
            'permission_name' => 'menu.master.department',
            'order' => 2
        ]);

          Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Proker',
            'url' => '/master/proker',
            'parent_id' => $master->id,
            'permission_name' => 'menu.master.proker',
            'order' => 3
        ]);
        $users = Menu::create([
            'id' => \Str::uuid(),
            'name' => 'User Management',
            'url' => '#',
            'permission_name' => 'menu.user.management',
            'order' => 3,
            'icon' => 'ix:user-management-filled'
        ]);

        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Permission',
            'url' => '/user/permission',
            'parent_id' => $users->id,
            'permission_name' => 'menu.user.permission',
            'order' => 1
        ]);
        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Role',
            'url' => '/user/role',
            'parent_id' => $users->id,
            'permission_name' => 'menu.user.role',
            'order' => 2
        ]);
        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'User',
            'url' => '/user/user',
            'parent_id' => $users->id,
            'permission_name' => 'menu.user.user',
            'order' => 3
        ]);
        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Menu',
            'url' => '/user/menu',
            'parent_id' => $users->id,
            'permission_name' => 'menu.user.menu',
            'order' => 4
        ]);

        $cms = Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Content Management',
            'url' => '#',
            'permission_name' => 'menu.cms',
            'order' => 4,
            'icon' => 'streamline-ultimate-color:layout-dashboard'
        ]);
        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Kegiatan',
            'url' => '/cms/activities',
            'parent_id' => $cms->id,
            'permission_name' => 'menu.cms.activities',
            'order' => 1
        ]);
        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Keuntungan',
            'url' => '/cms/benefits',
            'parent_id' => $cms->id,
            'permission_name' => 'menu.cms.benefits',
            'order' => 2
        ]);
        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'Berita',
            'url' => '/cms/news',
            'parent_id' => $cms->id,
            'permission_name' => 'menu.cms.news',
            'order' => 3
        ]);
      
        Menu::create([
            'id' => \Str::uuid(),
            'name' => 'FAQ',
            'url' => '/cms/faq',
            'parent_id' => $cms->id,
            'permission_name' => 'menu.cms.faq',
            'order' => 4
        ]);
    }
}
