<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'permission.view',
            'permission.create',
            'permission.edit',
            'permission.delete',

            'enumeration.view',
            'enumeration.create',
            'enumeration.edit',
            'enumeration.delete',

            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            'menu.view',
            'menu.create',
            'menu.edit',
            'menu.delete',

            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            'menu.dashboard',
            'menu.user.management',
            'menu.user.permission',
            'menu.user.role',
            'menu.user.user',
            'menu.user.menu',

            'menu.master',
            'menu.master.enumeration',
            'menu.master.department',
            'menu.master.proker',

            'menu.cms',
            'menu.cms.activities',
            'menu.cms.benefits',
            'menu.cms.news',
            'menu.cms.departments',
            'menu.cms.faq',
            'menu.cms.proker',

            'cms.activity.view',
            'cms.activity.create',
            'cms.activity.edit',
            'cms.activity.delete',

            'cms.benefit.view',
            'cms.benefit.create',
            'cms.benefit.edit',
            'cms.benefit.delete',

            'cms.news.view',
            'cms.news.create',
            'cms.news.show',
            'cms.news.edit',
            'cms.news.delete',

            'cms.departemen.view',
            'cms.departemen.create',
            'cms.departemen.edit',
            'cms.departemen.delete',

            'cms.faq.view',
            'cms.faq.create',
            'cms.faq.edit',
            'cms.faq.delete',

            'cms.proker.view',
            'cms.proker.create',
            'cms.proker.edit',
            'cms.proker.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api'
            ]);
        }

        $superadmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'api'
        ]);

        $superadmin->givePermissionTo(Permission::all());

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'api'
        ]);

        $admin->givePermissionTo([
            'permission.view',
            'permission.create',
            'permission.edit',
            'permission.delete',

            'enumeration.view',
            'enumeration.create',
            'enumeration.edit',
            'enumeration.delete',

            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            'menu.view',
            'menu.create',
            'menu.edit',
            'menu.delete',

            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            'menu.dashboard',

            'menu.master',
            'menu.master.enumeration',
            'menu.master.department',
            'menu.master.proker',

            'menu.cms',
            'menu.cms.activities',
            'menu.cms.benefits',
            'menu.cms.news',
            'menu.cms.departments',
            'menu.cms.faq',
            'menu.cms.proker',
        ]);

        $userSuperadmin = User::create([
            'id' => \Str::uuid(),
            'name' => 'Super Admin',
            'nim' => '0000000000',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $userAdmin = User::create([
            'id' => \Str::uuid(),
            'name' => 'Admin',
            'nim' => '0000000001',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $userSuperadmin->assignRole($superadmin);
        $userAdmin->assignRole($admin);
    }
}
