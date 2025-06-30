@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Laporan Statistik Semua Event</h3>

    <div class="row">
        @foreach ($statistik as $data)
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h5 class="mb-3 text-center">{{ $data['nama_event'] }}</h5>
                    <div class="row text-white">
                        <div class="col-6 mb-2">
                            <div class="card bg-primary">
                                <div class="card-body p-2 text-center">
                                    <small>Terdaftar</small>
                                    <h5>{{ $data['terdaftar'] }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="card bg-success">
                                <div class="card-body p-2 text-center">
                                    <small>Hadir</small>
                                    <h5>{{ $data['hadir'] }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="card bg-danger">
                                <div class="card-body p-2 text-center">
                                    <small>Tidak Hadir</small>
                                    <h5>{{ $data['tidak_hadir'] }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="card bg-warning text-dark">
                                <div class="card-body p-2 text-center">
                                    <small>Keterisian</small>
                                    <h5>{{ $data['persentase'] }}%</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Grafik ringkas --}}
                    <div class="text-center mt-3">
                        <canvas id="chart-{{ $loop->index }}" style="width: 100%; max-width: 150px; height: 150px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <h3 class="mb-4 mt-5">Statistik Seminar vs Workshop per Bulan</h3>
    <div class="card shadow">
        <div class="card-body">
            <canvas id="kategoriChart" height="120"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Chart per event --}}
@foreach ($statistik as $index => $data)
<script>
    new Chart(document.getElementById('chart-{{ $index }}'), {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Tidak Hadir'],
            datasets: [{
                data: [{{ $data['hadir'] }}, {{ $data['tidak_hadir'] }}],
                backgroundColor: ['#4CAF50', '#F44336'],
                borderWidth: 1
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: { size: 10 }
                    }
                }
            }
        }
    });
</script>
@endforeach

{{-- Line chart Seminar vs Workshop --}}
<script>
    const ctx = document.getElementById('kategoriChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Seminar',
                    data: {!! json_encode($seminarData) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Workshop',
                    data: {!! json_encode($workshopData) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
</script>
@endsection
