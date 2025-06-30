@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-4">Daftar Peserta: {{ $event->nama_event }}</h3>
        
        <form method="GET" class="d-flex" action="{{ route('peserta.listByEvent', $event->id_event) }}">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Cari nama peserta...">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <div class="row">
        @forelse ($registrations as $peserta)
            <div class="col-md-3 mb-4">
                <div class="card border-primary shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $peserta->nama_lengkap }}</h5>
                        <p class="card-text mb-4">
                            <strong>Status Bayar:</strong> {{ ucfirst($peserta->status_bayar) }}
                        </p>
                        <a href="{{ route('peserta.detail', $peserta->id_registration) }}" class="btn btn-sm btn-warning">Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info rounded-4">
                    Belum ada peserta terdaftar untuk event ini.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
