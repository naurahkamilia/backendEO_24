@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Detail Peserta</h3>

    <div class="card shadow-lg rounded-4 border-primary">
        <div class="card-body">
            <h4 class="card-title text-primary">{{ $peserta->nama_lengkap }}</h4>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>No. WhatsApp:</strong> {{ $peserta->no_whatsapp }}</p>
                    <p><strong>Jenis Kelamin:</strong> {{ $peserta->jenis_kelamin }}</p>
                    <p><strong>Status Bayar:</strong> 
                        <span class="badge bg-{{ $peserta->status_bayar === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($peserta->status_bayar) }}
                        </span>
                    </p>
                    <p><strong>Event:</strong> {{ $peserta->event->nama_event }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status Registrasi:</strong> 
                        <span class="badge bg-{{ $peserta->status_registrasi === 'active' ? 'info' : 'secondary' }}">
                            {{ ucfirst($peserta->status_registrasi ?? 'pending') }}
                        </span>
                    </p>
                    <p><strong>Status Kehadiran:</strong> 
                        <span class="badge bg-{{ $peserta->ticket && $peserta->ticket->status_hadir === 'hadir' ? 'success' : 'secondary' }}">
                            {{ $peserta->ticket && $peserta->ticket->status_hadir === 'hadir' ? 'Hadir' : 'Tidak Hadir' }}
                        </span>
                    </p>
                </div>
            </div>

            <form action="{{ route('peserta.update', $peserta->id_registration) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Status Pembayaran</label>
                    <select name="status_bayar" class="form-select" required>
                        <option value="paid" {{ $peserta->status_bayar == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ $peserta->status_bayar == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status_hadir" class="form-label">Status Kehadiran</label>
                    <select name="status_hadir" class="form-select" required>
                        <option value="tidak_hadir" {{ $peserta->ticket && $peserta->ticket->status_hadir == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                        <option value="hadir" {{ $peserta->ticket && $peserta->ticket->status_hadir == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    </select>
                </div>

                <!-- Tombol Edit -->
                <button type="submit" class="btn btn-warning">✏️ Edit Status</button>
                </form>

                <!-- Tombol Generate Sertifikat -->
                <a href="{{ route('admin.sertifikat.generate', $peserta->id_registration) }}" 
                class="btn btn-primary mt-3"
                onclick="return confirm('Apakah Anda yakin ingin membuat sertifikat untuk peserta ini?')">
                🎓 Generate Sertifikat
                </a>

                <!-- Tombol Kembali -->
                <br><br>
                <a href="{{ route('peserta.listByEvent', $peserta->id_event) }}" class="btn btn-secondary">
                    🔙 Kembali ke Daftar
                </a>
        </div>
    </div>
</div>
@endsection
