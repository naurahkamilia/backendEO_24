<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Registration;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceController extends Controller
{
    /**
     * Membuat tiket dan QR Code untuk peserta.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_registration' => 'required|exists:registrations,id_registration'
        ]);

        $registration = Registration::findOrFail($request->id_registration);

        // Jika tiket sudah ada, kembalikan
        if ($registration->ticket) {
            return response()->json([
                'message' => 'Ticket sudah tersedia',
                'data' => $registration->ticket
            ], 200);
        }

        $qrToken = Str::uuid();

        $ticket = Ticket::create([
            'id_registration' => $registration->id_registration,
            'qr_code' => $qrToken,
            'status_hadir' => 'tidak_hadir',
            'expired_at' => Carbon::now()->addDay() // berlaku 1 hari
        ]);

        return response()->json([
            'message' => 'Ticket berhasil dibuat',
            'data' => $ticket
        ], 201);
    }

    /**
     * Menampilkan halaman QR Code.
     */
    public function showQrCode($id_registration)
    {
        $registration = Registration::with('ticket')
            ->where('id_registration', $id_registration)
            ->where('status_registrasi', 'active')
            ->firstOrFail();

        $qrToken = $registration->ticket->qr_code;
        $qrCode = QrCode::format('svg')->size(250)->generate($qrToken);

        return view('admin.kehadiran.qrcode', compact('registration', 'qrCode'));
    }

    /**
     * Proses otomatis kehadiran via QR Token.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        $ticket = Ticket::where('qr_code', $request->qr_code)->first();

        if (!$ticket) {
            return response()->json(['message' => 'QR tidak valid'], 404);
        }

        if (Carbon::now()->greaterThan($ticket->expired_at)) {
            return response()->json(['message' => 'QR sudah kedaluwarsa'], 403);
        }

        if ($ticket->status_hadir === 'hadir') {
            return response()->json(['message' => 'Sudah ditandai hadir'], 200);
        }

        $ticket->status_hadir = 'hadir';
        $ticket->save();

        $registration = $ticket->registration;
        $registration->status_kehadiran = 'hadir';
        $registration->status_registrasi = 'complete';
        $registration->save();

        return response()->json(['message' => 'Kehadiran dicatat otomatis']);
    }

            public function generateCertificate($registration)
        {
            $event = $registration->event;
            $token = Str::uuid();

            // Ambil template dari event
            $templatePath = storage_path('app/public/' . $event->template_sertifikat);
            $img = Image::make($templatePath);

            // Tambahkan nama peserta
            $img->text($registration->nama_lengkap, 800, 600, function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(48);
                $font->color('#000');
                $font->align('center');
            });

            // Tambahkan token verifikasi
            $img->text("Kode: $token", 800, 700, function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(22);
                $font->color('#555');
                $font->align('center');
            });

            // Simpan sebagai image
            $tempPath = storage_path("app/public/temp_sertifikat_{$registration->id_registration}.png");
            $img->save($tempPath);

            // Konversi ke PDF
            $pdf = Pdf::loadView('sertifikat.image-to-pdf', [
                'image' => asset("storage/temp_sertifikat_{$registration->id_registration}.png")
            ]);

            $pdfPath = "sertifikat/sertifikat_{$registration->id_registration}.pdf";
            Storage::disk('public')->put($pdfPath, $pdf->output());

            // Simpan ke database
            \App\Models\Sertifikat::create([
                'id_registration' => $registration->id_registration,
                'qr_token' => $token,
                'path_pdf' => $pdfPath,
            ]);
            
        }
    public function autoMarkTidakHadir()
    {
        $tickets = Ticket::where('status_hadir', 'tidak_hadir')
            ->where('expired_at', '<=', Carbon::now())
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->status_hadir = 'tidak_hadir';
            $ticket->save();

            if ($ticket->registration) {
                $ticket->registration->status_kehadiran = 'tidak_hadir';
                $ticket->registration->status_registrasi = 'pending';
                $ticket->registration->save();
            }
        }

        return response()->json(['message' => 'Auto update ke tidak hadir selesai']);
    }
}
