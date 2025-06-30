@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Event</h2>
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('events.update', $event->id_event) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

        <div class="mb-3">
            <label for="nama_event" class="form-label">Nama Event</label>
            <input type="text" name="nama_event" class="form-control" value="{{ $event->nama_event }}" required>
        </div>

        <div class="mb-3">
            <label for="narasumber" class="form-label">Narasumber</label>
            <input type="text" name="narasumber" class="form-control" value="{{ $event->narasumber }}" required>
        </div>

        <div class="mb-3">
            <label for="kategori_event" class="form-label">Kategori</label>
            <select name="kategori_event" class="form-select" required>
                <option disabled>-- Pilih Kategori</option>
                <option value="seminar" {{ $event->kategori_event == 'seminar' ? 'selected' : '' }}>Seminar</option>
                <option value="workshop" {{ $event->kategori_event == 'workshop' ? 'selected' : '' }}>Workshop</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="jenis_event" class="form-label">Jenis</label>
            <select name="jenis_event" class="form-select" required>
                <option disabled>-- Pilih Jenis Event</option>
                <option value="free" {{ $event->jenis_event == 'free' ? 'selected' : '' }}>Free</option>
                <option value="paid" {{ $event->jenis_event == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_event" class="form-label">Tanggal</label>
            <input type="date" name="tanggal_event" class="form-control" value="{{ $event->tanggal_event }}" required>
        </div>

        <div class="mb-3">
            <label for="waktu_event" class="form-label">Waktu</label>
            <input type="time" name="waktu_event" class="form-control" value="{{ $event->waktu_event }}" required>
        </div>

        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ $event->lokasi }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ $event->deskripsi }}</textarea>
        </div>

        <div class="mb-3">
            <label for="benefit" class="form-label">Benefit</label>
            <textarea name="benefit" class="form-control" rows="3">{{ $event->benefit }}</textarea>
        </div>

        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan</label>
            <input type="text" name="catatan" class="form-control" value="{{ $event->catatan }}">
        </div>

        <div class="mb-3">
            <label for="kuota" class="form-label">Kuota</label>
            <input type="number" name="kuota" class="form-control" value="{{ $event->kuota }}" required>
        </div>

        <div class="mb-3">
            <label for="harga_event" class="form-label">Harga (HTM)</label>
            <input type="number" name="harga_event" class="form-control" value="{{ $event->harga_event }}">
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Poster</label>
            <input type="file" name="gambar" class="form-control">
            @if ($event->gambar)
                <small class="text-muted">Gambar saat ini: <strong>{{ $event->gambar }}</strong></small>
            @endif
        </div>

            <div class="mb-3">
        <label class="form-label">Template Sertifikat (JPG/PNG)</label>
        <input type="file" name="template_sertifikat" class="form-control">
         @if ($event->template_sertifikat)
                <small class="text-muted">Gambar saat ini: <strong>{{ $event->template_sertifikat }}</strong></small>
            @endif
    </div>


        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
