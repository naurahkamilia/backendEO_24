<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    // WEB/API : Simpan Data
    public function store(Request $request)
    {
    $request->validate([
        'id_event'       => 'required|exists:events,id_event',
        'nama_lengkap'   => 'required|string',
        'no_whatsapp'    => 'required|string',
        'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
        'instansi'       => 'required|string',
    ]);

    $alreadyRegistered = Registration::where('id_event', $request->id_event)
        ->where('id_users', auth()->id())
        ->exists();

    if ($alreadyRegistered) {
        return response()->json(['message' => 'Sudah terdaftar di event ini'], 409);
    }

    // Status default
    $statusBayar = $request->status_bayar ?? 'pending';
    $statusRegistrasi = $statusBayar === 'paid' ? 'active' : 'pending';

    // WEB / API : Simpan registrasi
    $registration = Registration::create([
        'id_event'          => $request->id_event,
        'id_users'          => Auth::id(),
        'nama_lengkap'      => $request->nama_lengkap,
        'no_whatsapp'       => $request->no_whatsapp,
        'jenis_kelamin'     => $request->jenis_kelamin,
        'instansi'          => $request->instansi,
        'participant'       => 1,
        'status_bayar'      => $statusBayar,
        'status_registrasi' => $statusRegistrasi,
        'status_hadir'      => 'tidak_hadir'
    ]);

    // BUAT TIKET HANYA JIKA STATUS BAYAR = PAID
    if ($statusBayar === 'paid') {
        Ticket::create([
            'id_registration' => $registration->id_registration,
            'qr_code'         => Str::uuid(),
            'status_hadir'    => 'tidak_hadir'
        ]);
    }

    return response()->json([
        'message' => 'Pendaftaran berhasil',
        'data' => $registration->load('ticket') // akan NULL jika belum ada tiket
    ], 201);
}

    // API : Ubah Data Peserta
    public function updateAPI(Request $request, $id){
    $request->validate([
        'nama_lengkap'   => 'required|string',
        'no_whatsapp'    => 'required|string',
        'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
        'instansi'       => 'required|string',
        'status_bayar'   => 'required|in:paid,pending',
        'status_hadir'   => 'required|in:hadir,tidak_hadir',
    ]);

    $registration = Registration::with('ticket', 'event')->findOrFail($id);

    // Update semua field
    $registration->nama_lengkap   = $request->nama_lengkap;
    $registration->no_whatsapp    = $request->no_whatsapp;
    $registration->jenis_kelamin  = $request->jenis_kelamin;
    $registration->instansi       = $request->instansi;
    $registration->status_bayar   = $request->status_bayar;

    // Status registrasi otomatis
    $registration->status_registrasi = $request->status_bayar === 'paid' ? 'active' : 'pending';

    if ($request->status_hadir === 'hadir') {
        $registration->status_registrasi = 'complete';
    }

    $registration->save();

    // Update atau buat tiket
    if ($registration->ticket) {
        $registration->ticket->status_hadir = $request->status_hadir;
        $registration->ticket->save();
    } elseif ($registration->status_bayar === 'paid') {
        \App\Models\Ticket::create([
            'id_registration' => $registration->id_registration,
            'qr_code' => \Illuminate\Support\Str::uuid(),
            'status_hadir' => $request->status_hadir,
        ]);
    }

    // Sertifikat jika memenuhi syarat
    if (
        $request->status_hadir === 'hadir' &&
        $registration->event &&
        $registration->event->jenis_event === 'paid' &&
        empty($registration->sertifikat) &&
        method_exists(app(\App\Http\Controllers\TicketController::class), 'generateCertificate')
    ) {
        app(\App\Http\Controllers\TicketController::class)->generateCertificate($registration);
    }

    // Reload relasi
    $registration->load('ticket', 'event');

    return response()->json([
        'message' => 'Data peserta berhasil diperbarui.',
        'data' => $registration
    ]);
}

    // API : Peserta berdasarkan Event
    public function getByEvent($id_event)
    {
        $registrations = Registration::with('user')
        ->where('id_event', $id_event)
        ->get();

    foreach ($registrations as $reg) {
    $user = $reg->user;

    if ($user->foto) {
        $user->foto_preview = asset('storage/foto/' . $user->foto);
    } else {
        $gender = strtolower($reg->jenis_kelamin);
        if ($gender === 'laki-laki') {
            $user->foto_preview = asset('storage/foto/default-man.jpg');
        } elseif ($gender === 'perempuan') {
            $user->foto_preview = asset('storage/foto/default-woman.jpg');
        } else {
            $user->foto_preview = asset('storage/foto/default-neutral.jpg');
        }
    }
}

    return response()->json([
        'message' => 'Data peserta',
        'data' => $registrations
    ]);
}

    // API : lihat detail peserta
    public function show($id)
    {
        $registration = Registration::with(['user', 'event'])->find($id);

        if (!$registration) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail peserta',
            'data' => $registration
        ]);
    }


    // WEB : Daftar peserta per event
    public function listByEvent(Request $request, $id_event)
    {
        $event = Event::findOrFail($id_event);

        $query = Registration::where('id_event', $id_event);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%")
                  ->orWhere('no_whatsapp', 'like', "%{$request->search}%")
                  ->orWhere('jenis_kelamin', 'like', "%{$request->search}%")
                  ->orWhere('instansi', 'like', "%{$request->search}%")
                  ->orWhere('status_bayar', 'like', "%{$request->search}%");
            });
        }

        $registrations = $query->get();
        return view('admin.peserta.list', compact('registrations', 'event'));
    }

    // WEB : Detail peserta
    public function detail($id)
    {
        $peserta = Registration::with('ticket')->findOrFail($id);
        return view('admin.peserta.detail', compact('peserta'));
    }

    // WEB : Ubah Data Pengguna
    public function update(Request $request, $id){
        $request->validate([
            'status_bayar' => 'required|in:paid,pending',
            'status_hadir' => 'required|in:hadir,tidak_hadir',
        ]);

        $registration = Registration::with('ticket', 'event')->findOrFail($id);

        // Update status bayar & registrasi
        $registration->status_bayar = $request->status_bayar;

        if ($request->status_bayar === 'paid') {
            $registration->status_registrasi = 'active';
        } else {
            $registration->status_registrasi = 'pending';
        }

        // Jika status hadir adalah 'hadir', maka upgrade status registrasi ke 'complete'
        if ($request->status_hadir === 'hadir') {
            $registration->status_registrasi = 'complete';
        }

        $registration->save();

        // Update atau buat tiket
        if ($registration->ticket) {
            $registration->ticket->status_hadir = $request->status_hadir;
            $registration->ticket->save();
        } elseif ($registration->status_bayar === 'paid') {
            Ticket::create([
                'id_registration' => $registration->id_registration,
                'qr_code' => Str::uuid(),
                'status_hadir' => $request->status_hadir,
            ]);
        }

    // Generate sertifikat jika syarat terpenuhi
    if (
        $request->status_hadir === 'hadir' &&
        $registration->event->jenis_event === 'paid' &&
        !$registration->sertifikat &&
        method_exists(app('App\Http\Controllers\TicketController'), 'generateCertificate')
    ) {
        app('App\Http\Controllers\TicketController')->generateCertificate($registration);
    }

    return redirect()
        ->route('peserta.listByEvent', $registration->id_event)
        ->with('success', 'Status peserta berhasil diperbarui.');
}


    // ✅ API Scan QR Kehadiran
    public function scanKehadiran(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $ticket = Ticket::where('qr_code', $request->qr_code)->first();

        if (!$ticket || !$ticket->registration) {
            return response()->json(['message' => 'QR Code tidak valid atau data tidak ditemukan.'], 404);
        }

        $registration = $ticket->registration;

        // Update kehadiran
        $ticket->status_hadir = 'hadir';
        $ticket->save();

        // Update status registrasi
        $registration->status_kehadiran = 'hadir';
        $registration->status_registrasi = 'complete';
        $registration->save();

        return response()->json([
            'message' => "Kehadiran peserta {$registration->nama_lengkap} berhasil dicatat."
        ]);
    }
}
