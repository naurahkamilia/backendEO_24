<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Registration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;

class TicketController extends Controller
{public function store(Request $request)
    {
        $request->validate([
            'id_registration' => 'required|exists:registrations,id_registration'
        ]);

        $registration = Registration::findOrFail($request->id_registration);

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
            'expired_at' => Carbon::now()->addDay()
        ]);

        // Generate QR code sebagai file PNG
        $qrImage = QrCode::format('png')->size(300)->generate($qrToken);
        $qrPath = 'qrcodes/qr_' . $ticket->id_ticket . '.png';
        Storage::disk('public')->put($qrPath, $qrImage);

        // Simpan path gambar QR ke kolom tambahan jika diinginkan (misal 'qr_path')
        $ticket->qr_path = $qrPath;
        $ticket->save();

        return response()->json([
            'message' => 'Ticket berhasil dibuat',
            'data' => $ticket
        ], 201);
    }

public function apiShowQrCode($id_registration)
{
    $registration = Registration::with('ticket')->where('id_registration', $id_registration)->first();

    if (!$registration || $registration->status_registrasi !== 'active') {
        return response()->json(['message' => 'Data pendaftaran tidak ditemukan atau tidak aktif.'], 404);
    }

    if (!$registration->ticket) {
        return response()->json(['message' => 'Tiket belum dibuat untuk peserta ini.'], 404);
    }

    $qrToken = $registration->ticket->qr_code;
    $qrSvg = QrCode::format('svg')->size(250)->generate($qrToken);
    $base64 = base64_encode($qrSvg);

    return response()->json([
        'message' => 'QR Code berhasil diambil',
        'data' => [
            'registration' => $registration,
            'qr_code_base64' => 'data:image/svg+xml;base64,' . $base64
        ]
    ]);
}

    /**
     * ✅ Admin Web: Tampilkan QR Code Tiket
     */
    public function showQrCode($id_registration)
    {
        $registration = Registration::with('ticket')
            ->where('id_registration', $id_registration)
            ->where('status_registrasi', 'active')
            ->firstOrFail();

        if (!$registration->ticket) {
            return redirect()->back()->with('error', 'Tiket belum dibuat untuk peserta ini.');
        }

        $qrToken = $registration->ticket->qr_code;

        $qrCode = QrCode::format('svg')
            ->size(250)
            ->generate($qrToken);

        return view('admin.kehadiran.qrcode', compact('registration', 'qrCode'));
    }

    /**
     * ✅ Admin Web: Generate Sertifikat + Verifikasi
     */
    public function generateCertificate(Registration $registration)
    {
        $event = $registration->event;

        // Validasi template
        if (!$event->template_sertifikat) {
            return back()->with('error', 'Template sertifikat belum tersedia.');
        }

        $templatePath = storage_path('app/public/' . $event->template_sertifikat);

        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template sertifikat tidak ditemukan.');
        }

        // Buat kode verifikasi
        $kode = strtoupper(Str::random(8));

        // Load template
        $image = Image::make($templatePath);

        // Tulis nama peserta
        $image->text(strtoupper($registration->nama_lengkap), 1000, 1300, function ($font) {
            $font->file(public_path('fonts/static/Roboto-Bold.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });

        // Tulis kode verifikasi
        $image->text("Kode: $kode", 1000, 1450, function ($font) {
            $font->file(public_path('fonts/static/Roboto-Regular.ttf'));
            $font->size(30);
            $font->color('#555555');
            $font->align('center');
        });

        // Path simpan
        $filename = 'sertifikat_' . $registration->id_registration . '.png';
        $relativePath = 'sertifikat/' . $filename;
        $absolutePath = storage_path('app/public/' . $relativePath);

        // Buat folder jika belum ada
        $folderPath = dirname($absolutePath);
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Simpan file
        $image->save($absolutePath);

        // Update registrasi
        $registration->update([
            'sertifikat' => $relativePath,
            'kode_verifikasi' => $kode,
        ]);

        return back()->with('success', 'Sertifikat berhasil dibuat.');
    }
}
