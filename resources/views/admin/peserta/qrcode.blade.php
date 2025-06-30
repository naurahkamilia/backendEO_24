@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h4>QR Code Kehadiran untuk: {{ $registration->nama_lengkap }}</h4>
    <div class="mt-3">
        {!! QrCode::size(200)->generate($qrCode) !!}
    </div>
    <p class="mt-2">Scan QR ini untuk mencatat kehadiran.</p>
</div>
@endsection
