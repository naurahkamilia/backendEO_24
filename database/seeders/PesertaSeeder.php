<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PesertaSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Liora Sasmita',
            'email' => 'liomita@example.com',
            'password' => Hash::make('password123'),
            'role' => 'peserta'
        ],
        [
            'nama' => 'Immanuel Danuar',
            'email' => 'danuar@example.com',
            'password' => Hash::make('password123'),
            'role' => 'peserta'
        ],
        [
            'nama' => 'Beno Oryza',
            'email' => 'ben@example.com',
            'password' => Hash::make('password123'),
            'role' => 'peserta'
        ],
        [
            'nama' => 'Talitha',
            'email' => 'tal@example.com',
            'password' => Hash::make('password123'),
            'role' => 'peserta'
        ]
    );
    }
}

