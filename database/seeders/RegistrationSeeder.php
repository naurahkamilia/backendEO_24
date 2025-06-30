<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Registration;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        Registration::create([
            'id_event' => 1, 
            'id_users' => 2,
            'nama_lengkap' => "Immanuel Danuar",
            'no_whatsapp' => "08123456789",
            'instansi' => 'Universitas Buana Perjuangan',
            'jenis_kelamin' => "Laki-laki",
            'participant' => 1,
        ]);

        Registration::create([
           'id_event' => 2, 
            'id_users' => 2,
            'nama_lengkap' => "Liora Sasmita",
            'no_whatsapp' => "08123456789",
            'instansi' => 'Universitas Buana Perjuangan',
            'jenis_kelamin' => "Perempuan",
            'participant' => 1,
        ],
    );    }
}
