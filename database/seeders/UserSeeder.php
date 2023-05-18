<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Admin',
            'email'=>'admin@test.com',
            'phone'=>'0417-999.88.77',
            'password'=>Hash::make('1234$Qwer'),
            'remember_token'=>Str::random(10)
        ])->assignRole('Admin');

        User::create([
            'name'=>'Rocio Macias',
            'email'=>'rmacias@test.com ',
            'phone'=>'0417-999.88.77',
            'password'=>Hash::make('1234$Qwer'),
            'remember_token'=>Str::random(10)
        ])->assignRole('Supervisor');

        User::create([
            'name'=>'Juan Medina',
            'email'=>'jmedina@test.com',
            'phone'=>'0417-999.88.77',
            'password'=>Hash::make('1234$Qwer'),
            'remember_token'=>Str::random(10)
        ])->assignRole('Usuario');

    }
}
