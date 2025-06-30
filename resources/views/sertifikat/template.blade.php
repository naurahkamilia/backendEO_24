<html>
<head><title>Sertifikat</title></head>
<body>
    <h1>Sertifikat</h1>
    <p>Nama: {{ $nama }}</p>
    <p>Kode Verifikasi: {{ $kode_verifikasi }}</p>
    <img src="{{ $qr_path }}" width="100" />
</body>
</html>