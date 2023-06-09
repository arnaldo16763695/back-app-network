<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creacion de los permisos
        Permission::create(['name'=>'user.index']);
        Permission::create(['name'=>'user.show']);
        Permission::create(['name'=>'user.update']);
        Permission::create(['name'=>'user.delete']);

        Permission::create(['name'=>'auth.login']);
        Permission::create(['name'=>'auth.register']);
        Permission::create(['name'=>'auth.resetPassword']);
        Permission::create(['name'=>'auth.changePassword']);
        Permission::create(['name'=>'auth.logout']);
        Permission::create(['name'=>'auth.roletouser']);
        Permission::create(['name'=>'auth.rmvroletouser']);

        Permission::create(['name'=>'roles.index']);

        Permission::create(['name'=>'devices.index']);
        Permission::create(['name'=>'devices.show']);
        Permission::create(['name'=>'devices.register']);
        Permission::create(['name'=>'devices.update']);
        Permission::create(['name'=>'devices.delete']);

        Permission::create(['name'=>'status.index']);
        Permission::create(['name'=>'status.show']);
        Permission::create(['name'=>'status.register']);
        Permission::create(['name'=>'status.update']);
        Permission::create(['name'=>'status.delete']);

        Permission::create(['name'=>'types.index']);
        Permission::create(['name'=>'types.show']);
        Permission::create(['name'=>'types.register']);
        Permission::create(['name'=>'types.update']);
        Permission::create(['name'=>'types.delete']);

        Permission::create(['name'=>'headquarters.index']);
        Permission::create(['name'=>'headquarters.show']);
        Permission::create(['name'=>'headquarters.register']);
        Permission::create(['name'=>'headquarters.update']);
        Permission::create(['name'=>'headquarters.delete']);

        Permission::create(['name'=>'locations.index']);
        Permission::create(['name'=>'locations.show']);
        Permission::create(['name'=>'locations.register']);
        Permission::create(['name'=>'locations.update']);
        Permission::create(['name'=>'locations.delete']);
        Permission::create(['name'=>'locations.locHead']);

        // Creacion de los roles & asignacion de permisos
        // Role: Admin
        Role::create(['name'=>'Admin'])
        ->givePermissionTo([
            'auth.login',
            'user.index',
            'user.show',
            'user.update',
            'user.delete',

            'auth.register',
            'auth.resetPassword',
            'auth.changePassword',
            'auth.logout',
            'auth.roletouser',
            'auth.rmvroletouser',

            'roles.index',

            'devices.index',
            'devices.show',
            'devices.register',
            'devices.update',
            'devices.delete',

            'status.index',
            'status.show',
            'status.register',
            'status.update',
            'status.delete',

            'types.index',
            'types.show',
            'types.register',
            'types.update',
            'types.delete',

            'headquarters.index',
            'headquarters.show',
            'headquarters.register',
            'headquarters.update',
            'headquarters.delete',

            'locations.index',
            'locations.show',
            'locations.register',
            'locations.update',
            'locations.delete',
            'locations.locHead'
        ]);

        // Role: Supervisor
        Role::create(['name'=>'Supervisor'])
        ->givePermissionTo([
            'auth.login',
            'user.index',
            'user.show',

            'auth.register',
            'auth.changePassword',
            'auth.logout',

            'devices.index',
            'devices.show',
            'devices.register',
            'devices.update',

            'status.index',
            'status.show',
            'status.register',
            'status.update',

            'types.index',
            'types.show',
            'types.register',
            'types.update',

            'headquarters.index',
            'headquarters.show',
            'headquarters.register',
            'headquarters.update',

            'locations.index',
            'locations.show',
            'locations.register',
            'locations.update',
            'locations.locHead'
        ]);

        // Role: Usuario
        Role::create(['name'=>'Usuario'])
        ->givePermissionTo([
            'auth.login',

            'auth.changePassword',
            'auth.logout',

            'devices.index',
            'devices.show',

            'locations.index',
            'locations.show',
            'locations.locHead',

            'headquarters.index',
            'headquarters.show'
        ]);
    }
}
