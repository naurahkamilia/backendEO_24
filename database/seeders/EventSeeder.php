<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run()
    {
        Event::insert([
            [
                'nama_event'      => 'Seminar Teknologi AI',
                'narasumber'      => 'Dr. Andi Wijaya',
                'kategori_event'  => 'seminar',
                'jenis_event'     => 'free',
                'tanggal_event'   => now()->addDays(5)->format('Y-m-d'),
                'waktu_event'     => '09:00:00',
                'lokasi'          => 'Auditorium Kampus A',
                'deskripsi'       => 'Diskusi tentang AI dan penerapannya di masa depan.',
                'benefit'         => 'E-Sertifikat, Makan Siang',
                'catatan'         => 'Datang 30 menit sebelum acara',
                'kuota'           => 150,
                'gambar'          => 'seminar-ai.jpg',
                'harga_event'     => 0,
                'created_by'      => 1,
                'created_at'      => now(),
                'updated_at'      => now()
            ],
            [
                'nama_event'      => 'Workshop UI/UX Design',
                'narasumber'      => 'Rina Kartika, S.Kom',
                'kategori_event'  => 'workshop',
                'jenis_event'     => 'paid',
                'tanggal_event'   => now()->addDays(10)->format('Y-m-d'),
                'waktu_event'     => '13:30:00',
                'lokasi'          => 'Ruang Kreatif Lt. 3',
                'deskripsi'       => 'Pelatihan intensif UI/UX Design untuk pemula.',
                'benefit'         => 'Modul, E-Sertifikat, Snack',
                'catatan'         => 'Bawa laptop sendiri',
                'kuota'           => 40,
                'gambar'          => 'workshop-uiux.png',
                'harga_event'     => 50000,
                'created_by'      => 1,
                'created_at'      => now(),
                'updated_at'      => now()
            ]
        ]);
    }
}
