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
