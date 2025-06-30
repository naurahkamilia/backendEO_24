@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h3 class="mb-4">QR Code Kehadiran</h3>
    <h5>{{ $registration->nama_lengkap }}</h5>
    <div>{!! $qrCode !!}</div>
</div>
@endsection
