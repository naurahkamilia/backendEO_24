<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventPesertaController extends Controller
{
    public function index()
    {
        // Menampilkan semua event yang belum lewat tanggalnya
        $events = Event::whereDate('tanggal_event', '>=', now())
            ->orderBy('tanggal_event')
            ->get([
                'id_event',
                'nama_event',
                'kategori_event',
                'jenis_event',
                'tanggal_event',
                'waktu_event',
                'lokasi',
                'kuota',
                'deskripsi',
                'gambar'
            ]);

        return response()->json([
            'message' => 'Daftar event tersedia',
            'data' => $events
        ]);
    }

    public function show($id)
    {
        $event = Event::where('id_event', $id)->firstOrFail();

// Tambahkan URL lengkap untuk gambar
        $gambarUrl = null;
        if ($event->gambar && \Storage::disk('public')->exists($event->gambar)) {
            $gambarUrl = asset('storage/' . $event->gambar);
        }

        return response()->json([
            'message' => 'Detail event',
            'data' => [
                'id_event' => $event->id_event,
                'nama_event'=> $event->nama_event,
                'narasumber'=> $event->narasumber,
                'kategori_event'=> $event->kategori_event,
                'jenis_event'=> $event->jenis_event,
                'tanggal_event'=> $event->tanggal_event,
                'waktu_event'=> $event->waktu_event,
                'lokasi'=> $event->lokasi,
                'deskripsi'=> $event->deskripsi,
                'benefit'=> $event->benefit,
                'catatan'=> $event->catatan,
                'link_wa'=> $event->link_wa,
                'kuota'=> $event->kuota,
                'harga_event'=> $event->harga_event,
                'gambar'       => $gambarUrl, 
            ]
        ]);
    }
}
