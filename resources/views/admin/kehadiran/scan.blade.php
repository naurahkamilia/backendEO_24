@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4 text-center">Scan QR Kehadiran Peserta</h3>

    <div class="d-flex justify-content-center">
        <div id="qr-reader" style="width: 400px;"></div>
    </div>

    <div class="mt-4 text-center" id="result" style="font-weight: bold;"></div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('result').innerText = "QR Terdeteksi: " + decodedText;

        // Kirim ke backend untuk konfirmasi kehadiran
        fetch("/api/kehadiran/scan", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ qr_code: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                alert("Kehadiran dikonfirmasi untuk: " + data.nama);
            } else {
                alert("Gagal: " + data.message);
            }
        })
        .catch(err => {
            alert("Error kirim data");
            console.error(err);
        });
    }

    let scanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
    scanner.render(onScanSuccess);
</script>
@endsection
