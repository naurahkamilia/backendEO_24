@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Event</h2>
    <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">+ Tambah Event</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Gambar</th> 
                <th>Nama Event</th>
                <th>Jadwal</th>
                <th>Lokasi</th>
                <th>Jenis</th>
                <th>Kuota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>
                        @php
                            $gambarPath = $event->gambar;
                            $fullStoragePath = public_path('storage/' . $gambarPath);
                        @endphp

                        @if ($gambarPath && file_exists($fullStoragePath))
                            <img src="{{ asset('storage/' . $gambarPath) }}" alt="Gambar Event" width="100">
                        @else
                            <span class="text-muted">Tidak ada gambar</span>
                        @endif
                    </td>

                    <td>{{ $event->nama_event }}</td>
                    <td>{{ $event->tanggal_event }} - {{ $event->waktu_event }}</td>
                    <td>{{ $event->lokasi }}</td>
                    <td>{{ ucfirst($event->jenis_event) }}</td>
                    <td>{{ $event->kuota }}</td>
                    <td>
                        <a href="{{ route('events.edit', $event->id_event) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('events.destroy', $event->id_event) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
