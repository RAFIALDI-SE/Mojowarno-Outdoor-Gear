<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'rapgantengdewe@gmail.com'], // biar ga dobel
            [
                'name'     => 'Admin Mojowarno',
                'phone'    => '081234567893',
                'role'     => 'admin',
                'address'  => 'Mojowarno, Jombang',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
    }
}
