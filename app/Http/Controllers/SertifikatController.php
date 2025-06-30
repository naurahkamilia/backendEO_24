<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Models\Sertifikat;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class SertifikatController extends Controller
{
    /**
     * Generate sertifikat untuk peserta berdasarkan ID registrasi
     */
    public function generate($registrationId): RedirectResponse
    {
        // Ambil data registrasi lengkap dengan relasinya
        $data = Registration::with(['user', 'event', 'ticket'])->findOrFail($registrationId);

        // Validasi kehadiran peserta
        if (!$data->ticket || $data->ticket->status_hadir !== 'hadir') {
            return back()->with('error', 'Sertifikat hanya bisa dibuat jika peserta sudah hadir.');
        }

        // Cek duplikasi sertifikat
        $existing = Sertifikat::where('id_users', $data->user->id_users)
                              ->where('id_event', $data->event->id_event)
                              ->first();
        if ($existing) {
            return back()->with('error', 'Sertifikat sudah pernah dibuat untuk peserta ini.');
        }

        // Pastikan template sertifikat tersedia
        if (!$data->event->template_sertifikat) {
            return back()->with('error', 'Template sertifikat belum tersedia.');
        }

        $templatePath = public_path('storage/' . $data->event->template_sertifikat);
        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template sertifikat tidak ditemukan.');
        }

        // Buat direktori untuk QR code jika belum ada
        Storage::makeDirectory('public/qrcodes');

        // Buat kode verifikasi unik
        $kodeVerifikasi = strtoupper(Str::random(8));

        // Generate QR code dan simpan ke storage
        $qrCodeData = QrCode::format('png')
            ->size(200)
            ->generate(route('admin.sertifikat.verifikasi', $kodeVerifikasi));

        $qrFilename = $kodeVerifikasi . '.png';
        $qrStoragePath = 'public/qrcodes/' . $qrFilename;
        Storage::put($qrStoragePath, $qrCodeData);

        $qrAbsolutePath = storage_path('app/' . $qrStoragePath);

        // Load template sertifikat
        $img = Image::make($templatePath);

        // Tambahkan nama peserta
        $img->text(strtoupper($data->nama_lengkap), 1000, 1300, function ($font) {
            $fontPath = file_exists(public_path('fonts/static/Roboto-Bold.ttf'))
                ? public_path('fonts/static/Roboto-Bold.ttf')
                : public_path('fonts/arial.ttf');
            $font->file($fontPath);
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });

        // Tambahkan kode verifikasi
        $img->text("Kode: $kodeVerifikasi", 1000, 1450, function ($font) {
            $fontPath = file_exists(public_path('fonts/static/Roboto-Regular.ttf'))
                ? public_path('fonts/static/Roboto-Regular.ttf')
                : public_path('fonts/arial.ttf');
            $font->file($fontPath);
            $font->size(30);
            $font->color('#555555');
            $font->align('center');
        });

        // Sisipkan QR code ke kanan bawah
        $img->insert($qrAbsolutePath, 'bottom-right', 100, 100);

        // Simpan sertifikat
        $filename = 'sertifikat_' . $data->user->id_users . '_' . $data->event->id_event . '.png';
        $pathToSave = 'public/sertifikat/' . $filename;
        Storage::put($pathToSave, (string) $img->encode('png'));

        // Simpan ke database
        Sertifikat::create([
            'id_users'        => $data->user->id_users,
            'id_event'        => $data->event->id_event,
            'sertifikat_path' => 'sertifikat/' . $filename,
            'kode_verifikasi' => $kodeVerifikasi,
        ]);

        return redirect()
            ->route('admin.sertifikat.index', $data->event->id_event)
            ->with('success', 'Sertifikat berhasil dibuat untuk ' . $data->nama_lengkap);
    }

    public function checkFonts()
    {
        dd([
            'bold'    => file_exists(public_path('fonts/static/Roboto-Bold.ttf')),
            'regular' => file_exists(public_path('fonts/static/Roboto-Regular.ttf')),
        ]);
    }

public function generateAPI(Request $request, $registrationId)
{
    $data = Registration::with(['user', 'event', 'ticket'])->findOrFail($registrationId);

    if ($data->event->jenis_event !== 'paid') {
        return response()->json(['message' => 'Event gratis tidak mendapatkan sertifikat.'], 403);
    }

    if (!$data->ticket || $data->ticket->status_hadir !== 'hadir') {
        return response()->json(['message' => 'Sertifikat hanya bisa dibuat jika peserta sudah hadir.'], 403);
    }

    $existing = Sertifikat::where('id_users', $data->user->id_users)
        ->where('id_event', $data->event->id_event)
        ->first();
    if ($existing) {
        return response()->json(['message' => 'Sertifikat sudah pernah dibuat.'], 409);
    }

    $kodeVerifikasi = strtoupper(Str::random(8));

    $qrCode = new QrCode(url('/api/certificate/verifikasi/' . $kodeVerifikasi));
    $writer = new PngWriter();
    $result = $writer->write($qrCode);
    $qrFilename = $kodeVerifikasi . '.png';

    Storage::put('public/qrcodes/' . $qrFilename, $result->getString());

    $pdf = Pdf::loadView('sertifikat.template', [
        'nama' => $data->nama_lengkap,
        'kode_verifikasi' => $kodeVerifikasi,
        'qr_path' => storage_path('app/public/qrcodes/' . $qrFilename),
        'template' => $data->event->template_sertifikat,
    ]);

    $filename = 'sertifikat_' . $data->user->id_users . '_' . $data->event->id_event . '.pdf';
    $path = 'public/sertifikat/' . $filename;
    Storage::put($path, $pdf->output());

    Sertifikat::create([
        'id_users' => $data->user->id_users,
        'id_event' => $data->event->id_event,
        'sertifikat_path' => 'sertifikat/' . $filename,
        'kode_verifikasi' => $kodeVerifikasi,
    ]);

    return response()->json([
        'message' => 'Sertifikat berhasil dibuat.',
        'sertifikat_url' => url('storage/sertifikat/' . $filename)
    ]);
}
public function verifikasiAPI($kode)
{
    $sertifikat = Sertifikat::where('kode_verifikasi', $kode)->first();

    if (!$sertifikat) {
        return response()->json(['message' => 'Kode verifikasi tidak ditemukan.'], 404);
    }

    return response()->json([
        'message' => 'Sertifikat valid.',
        'data' => [
            'id_users' => $sertifikat->id_users,
            'id_event' => $sertifikat->id_event,
            'sertifikat_url' => url('storage/' . $sertifikat->sertifikat_path),
            'kode_verifikasi' => $sertifikat->kode_verifikasi,
        ]
    ]);
}

}