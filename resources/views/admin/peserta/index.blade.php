@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Pilih Event untuk Melihat Peserta</h3>

    <div class="row">
        @foreach ($events as $event)
        <div class="col-md-3 mb-4">
            <a href="{{ route('peserta.listByEvent', ['id_event' => $event->id_event]) }}" class="card text-center shadow card-animate">
                <div class="card text-center shadow" style="background: linear-gradient(to bottom, #6ec1e4, #2980b9); color: white;">
                    <div class="card-body">
                        <div style="font-size: 40px;">🎫</div> <!-- Ganti dengan ikon sesuai kategori -->
                        <h5 class="card-title mt-2">{{ $event->nama_event }}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
