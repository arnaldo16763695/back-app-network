<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Admin = Role::create(['name'=>'Admin']);
        $Supervisor = Role::create(['name'=>'Supervisor']);
        $Usuario = Role::create(['name'=>'Usuario']);

        Permission::create(['name'=>'auth.register'])->syncRoles([$Admin]);
        Permission::create(['name'=>'auth.roletouser'])->syncRoles([$Admin]);
        Permission::create(['name'=>'auth.login'])->syncRoles([$Admin, $Supervisor, $Usuario]);
        Permission::create(['name'=>'auth.logout'])->syncRoles([$Admin, $Supervisor, $Usuario]);
        Permission::create(['name'=>'user.index'])->syncRoles([$Admin, $Supervisor, $Usuario]);
        Permission::create(['name'=>'user.show'])->syncRoles([$Admin, $Supervisor, $Usuario]);
        Permission::create(['name'=>'user.update'])->syncRoles([$Admin, $Supervisor]);
        Permission::create(['name'=>'user.delete'])->syncRoles([$Admin]);
    }
}
