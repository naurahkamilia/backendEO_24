<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Attendance;
use App\Models\Ticket;

class PesertaController extends Controller
{
    // ✅ 1. Tampilkan daftar peserta per event dengan pencarian & paginasi
    public function index(Request $request, $id)
    {
        $query = Registration::with(['user', 'ticket.attendance'])
            ->where('id_event', $id);

        // 🔍 Filter nama peserta
        if ($request->has('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        // ⏱️ Hindari timeout dengan paginasi
        $registrations = $query->paginate(20);

        return response()->json([
            'data' => $registrations->map(function ($reg) {
                return [
                    'id_registration' => $reg->id_registration,
                    'nama' => $reg->user->nama,
                    'no_whatsapp' => $reg->no_whatsapp,
                    'jenis_kelamin' => $reg->jenis_kelamin,
                    'status_kehadiran' => optional($reg->ticket->attendance)->status_attd ?? 'Belum diatur'
                ];
            }),
            'meta' => [
                'current_page' => $registrations->currentPage(),
                'last_page' => $registrations->lastPage(),
                'total' => $registrations->total()
            ]
        ]);
    }

    // ✅ 2. Update status kehadiran peserta
    public function updateAttendance(Request $request, $registration_id)
    {
        $request->validate([
            'status' => 'required|in:hadir,tidak hadir'
        ]);

        // Cek tiket milik peserta
        $ticket = Ticket::where('id_registration', $registration_id)->firstOrFail();

        // Update atau buat status kehadiran
        $attendance = Attendance::updateOrCreate(
            ['id_ticket' => $ticket->id_ticket],
            ['status_attd' => $request->status]
        );

        return response()->json([
            'message' => 'Status kehadiran berhasil diperbarui',
            'data' => $attendance
        ]);
    }

    public function getUser()
    {
        $user = Auth::user();

        $registrations = Registration::with('event')
            ->where('id_users', $user->id)
            ->get()
            ->map(function ($reg) {
                logger($reg);
                return [
                    'id_registration' => $reg->id_registration, // pastikan field ini ada
                    'status_registrasi' => $reg->status_registrasi,
                    'total_bayar' => $reg->total_bayar ?? 0,
                    'event' => [
                        'id' => $reg->event->id_event ?? '',
                        'nama_event' => $reg->event->nama_event ?? '',
                        'gambar' => $reg->event->gambar ?? '',
                        'tanggal_event' => $reg->event->tanggal_event ?? ''
                    ]
                ];
            });

        return response()->json([
            'user' => $user,
            'registrations' => $registrations
        ]);
    }
}
