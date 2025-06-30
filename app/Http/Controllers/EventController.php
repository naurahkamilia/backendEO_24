<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 

class EventController extends Controller
{
    // API: Ambil semua event

public function apiIndex()
{
    $events = Event::orderBy('tanggal_event')->get();

    $events = $events->map(function ($event) {
        $event->gambar = $event->gambar ? asset($event->gambar) : null;
        return $event;
    });

    return response()->json([
        'message' => 'Daftar event',
        'data' => $events
    ]);
}

public function showAPI($id)
{
    $event = Event::find($id);

    if (!$event) {
        return response()->json(['message' => 'Event tidak ditemukan'], 404);
    }

    $gambar_url = null;
    if ($event->gambar && Storage::disk('public')->exists($event->gambar)) {
        $gambar_url = asset('storage/' . $event->gambar);
    }

    $data = $event->toArray();
    $data['id_event'] = $event->getKey();

    return response()->json([
        'message' => 'Detail event',
        'data' => $data
    ]);
}
   // WEB: Tampilkan daftar event
    public function index()
    {
        $events = Event::all();
        return view('admin.events.index', compact('events'));
    }

    // WEB: Tampilkan daftar event untuk peserta
    public function indexParticipant()
    {
        $events = Event::all();
        return view('admin.peserta.index', compact('events'));
    }

    // WEB: Tampilkan form tambah event
    public function create()
    {
        return view('admin.events.create');
    }

    // WEB: Tampilkan form edit event
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    // Simpan event baru (WEB/API)
public function store(Request $request)
{
    if (strlen($request->waktu_event) === 5) {
        $request->merge([
            'waktu_event' => $request->waktu_event . ':00'
        ]);
    }

    // Validasi input
    $validated = $request->validate([
        'nama_event'     => 'required|string',
        'narasumber'     => 'required|string',
        'kategori_event' => 'required|in:seminar,workshop',
        'jenis_event'    => 'required|in:free,paid',
        'tanggal_event'  => 'required|date',
        'waktu_event'    => 'required|date_format:H:i:s',
        'lokasi'         => 'required|string',
        'deskripsi'      => 'nullable|string',
        'benefit'        => 'nullable|string',
        'catatan'        => 'nullable|string',
        'link_wa'        => 'nullable|string',
        'kuota'          => 'required|integer',
        'harga_event'    => 'nullable|integer',
        'gambar'         => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        'template_sertifikat' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
    ]);

    // Upload file gambar jika ada
    $gambarPath = null;
    if ($request->hasFile('gambar')) {
        $gambarPath = $request->file('gambar')->store('posters', 'public');
    }

    $templatePath = null;
    if ($request->hasFile('template_sertifikat')) {
        $templatePath = $request->file('template_sertifikat')->store('templates', 'public');
    }

    // Buat event
    $event = Event::create([
        'nama_event'     => $validated['nama_event'],
        'narasumber'     => $validated['narasumber'],
        'kategori_event' => $validated['kategori_event'],
        'jenis_event'    => $validated['jenis_event'],
        'tanggal_event'  => $validated['tanggal_event'],
        'waktu_event'    => $validated['waktu_event'],
        'lokasi'         => $validated['lokasi'],
        'deskripsi'      => $validated['deskripsi'] ?? null,
        'benefit'        => $validated['benefit'] ?? null,
        'catatan'        => $validated['catatan'] ?? null,
        'link_wa'        => $validated['link_wa'] ?? null,
        'kuota'          => $validated['kuota'],
        'harga_event'    => $validated['harga_event'] ?? 0,
        'gambar'         => $gambarPath,
        'template_sertifikat' => $templatePath,
        'created_by'     => Auth::id(),
    ]);

    // Cek apakah API atau web
    if ($request->wantsJson()) {
        return response()->json([
            'message' => 'Event berhasil dibuat',
            'event' => $event
        ], 201);
    }

    return redirect()->route('events.index')->with('success', 'Event berhasil disimpan.');
}


    // WEB: Detail event
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    // Update event (WEB/API)
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'nama_event'     => 'required|string',
            'narasumber'     => 'required|string',
            'kategori_event' => 'required|in:seminar,workshop',
            'jenis_event'    => 'required|in:free,paid',
            'tanggal_event'  => 'required|date',
            'waktu_event'    => 'required|date_format:H:i:s',
            'lokasi'         => 'required|string',
            'deskripsi'      => 'nullable|string',
            'benefit'        => 'nullable|string',
            'catatan'        => 'nullable|string',
            'link_wa'        => 'nullable|string',
            'kuota'          => 'required|integer',
            'harga_event'    => 'nullable|integer',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'template_sertifikat'    => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // Ganti gambar jika diupload
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('posters', 'public');
            if ($event->gambar) {
                \Storage::disk('public')->delete($event->gambar);
            }
        }

        if ($request->hasFile('template_sertifikat')) {
        $templatePath = $request->file('template_sertifikat')->store('templates', 'public');
        $event->template_sertifikat = $templatePath;
        $event->save();
}

        $jenisSebelumnya = $event->jenis_event;

        // Update
        $event->update($validated);

        // Response API
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Event berhasil diperbarui', 'event' => $event]);
        }

        if ($request->hasFile('template_sertifikat')) {
        $templatePath = $request->file('template_sertifikat')->store('templates', 'public');
        $event->template_sertifikat = $templatePath;
        $event->save();
}

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui.');
    }

    // Hapus event (WEB/API)
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Event berhasil dihapus']);
        }

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus.');
    }
}
