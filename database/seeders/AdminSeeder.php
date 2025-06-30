<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Admin Sistem',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ],
        [
            'nama' => 'Admin Umum',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]
    );
    }
}

