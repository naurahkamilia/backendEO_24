@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Event Baru</h2>

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


    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nama_event" class="form-label">Nama Event</label>
            <input type="text" name="nama_event" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="narasumber" class="form-label">Narasumber</label>
            <input type="text" name="narasumber" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="kategori_event" class="form-label">Kategori</label>
            <select name="kategori_event" class="form-select" required>
                <option>-- Pilih Kategori</option>
                <option value="seminar">Seminar</option>
                <option value="workshop">Workshop</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="jenis_event" class="form-label">Jenis</label>
            <select name="jenis_event" class="form-select" required>
                <option value="">-- Pilih Jenis Event</option>
                <option value="free">Free</option>
                <option value="paid">Paid</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_event" class="form-label">Tanggal</label>
            <input type="date" name="tanggal_event" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="waktu_event" class="form-label">Waktu</label>
            <input type="time" name="waktu_event" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="benefit" class="form-label">Benefit</label>
             <textarea name="benefit" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
        <label for="kuota" class="form-label">Kuota</label>
        <select name="kuota" class="form-select" required>
            <option value="">-- Pilih Kuota</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
            <option value="custom">lainnya...</option>
        </select>
        </div>

        <div class="mb-3">
            <label for="harga_event" class="form-label">Harga (HTM)</label>
            <input type="text" name="harga_event" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="link_wa" class="form-label">Link WhatsApp</label>
            <input type="text" name="link_wa" class="form-control">
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Poster</label>
            <input type="file" name="gambar" class="form-control">
        </div>

        <div class="mb-3">
        <label class="form-label">Template Sertifikat (JPG/PNG)</label>
        <input type="file" name="template_sertifikat" class="form-control">
    </div>


        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
