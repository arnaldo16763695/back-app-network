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
        Permission::create(['name'=>'auth.register']);
        Permission::create(['name'=>'auth.roletouser']);
        Permission::create(['name'=>'auth.rmvroletouser']);
        Permission::create(['name'=>'auth.login']);
        Permission::create(['name'=>'auth.logout']);
        Permission::create(['name'=>'user.index']);
        Permission::create(['name'=>'user.show']);
        Permission::create(['name'=>'user.update']);
        Permission::create(['name'=>'user.delete']);

        // Creacion de los roles & asignacion de permisos
        // Role: Admin
        Role::create(['name'=>'Admin'])
        ->givePermissionTo([
            'auth.register',
            'auth.roletouser',
            'auth.rmvroletouser',
            'auth.login',
            'auth.logout',
            'user.index',
            'user.show',
            'user.update',
            'user.delete'
        ]);

        // Role: Supervisor
        Role::create(['name'=>'Supervisor'])
        ->givePermissionTo([
            'auth.login',
            'auth.logout',
            'user.index',
            'user.show',
            'user.update'
        ]);

        // Role: Usuario
        Role::create(['name'=>'Usuario'])
        ->givePermissionTo([
            'auth.login',
            'auth.logout',
            'user.index',
            'user.show'
        ]);
    }
}
