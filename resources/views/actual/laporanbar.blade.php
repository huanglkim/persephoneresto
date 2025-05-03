@extends('layouts.app')

@section('title', 'Pendapatan per Bulan')

@section('css')
    <style>
        #filterForm .form-group input {
            width: 100%;
            /* Membuat input mengisi lebar container */
            max-width: 450px;
            /* Memberikan batas lebar maksimum */
        }

        /* Menata form agar elemen-elemennya lebih terstruktur */
        #filterForm .row.g-3 {
            margin-bottom: 1rem;
            /* Memberikan sedikit ruang di bawah baris input */
        }

        /* Membuat tombol filter mengisi lebar kolomnya */
        #filterForm .col-md-3.d-flex.align-items-end button {
            width: 100%;
        }

        #filterForm .form-control {
            max-width: 100%;
            /* Pastikan input responsif penuh */
        }

        @media (min-width: 768px) {
            #filterForm .form-control {
                max-width: 100%;
                /* Biarkan Bootstrap atur dengan grid */
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filter Data</h5>
            </div>
            <div class="card-body">
                <form id="filterForm" method="POST" action="{{ route('laporan.bar') }}">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="bulan_awal" class="form-label">Bulan Awal:</label>
                            <input type="month" id="bulan_awal" name="bulan_awal" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="bulan_akhir" class="form-label">Bulan Akhir:</label>
                            <input type="month" id="bulan_akhir" name="bulan_akhir" class="form-control" required>
                        </div>
                        <div class="col-md-4 d-grid">
                            <button type="submit" class="btn btn-modern">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Grafik Pendapatan per Bulan</h5>
            </div>
            <canvas id="pendapatan" width="400" height="200"></canvas>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        $(document).ready(function() {
            $('#filterForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        const ctx = document.getElementById('pendapatan').getContext('2d');
                        const labels = Object.keys(response.data);
                        const datasets = [];
                        const colors = [
                            'rgba(123, 104, 237, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(220, 20, 60, 0.7)',
                            'rgba(139, 0, 139, 0.7)',
                            'rgba(30, 144, 255, 0.7)',
                            'rgba(34, 139, 34, 0.7)',
                            'rgba(100, 149, 237, 0.7)',
                            'rgba(124, 252, 0, 0.7)',
                            /* dan seterusnya */
                        ];
                        let colorIndex = 0; // Indeks warna untuk setiap user

                        for (const user in response.data[labels[0]]) {
                            const data = labels.map(bulan => response.data[bulan][user] ?? 0);
                            datasets.push({
                                label: user,
                                data: data,
                                backgroundColor: colors[colorIndex % colors.length],
                                borderColor: colors[colorIndex % colors.length],
                                borderWidth: 1
                            });
                            colorIndex++;
                        }

                        if (window.myChart) {
                            window.myChart.destroy();
                        }

                        window.myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: datasets
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value, index, values) {
                                                return value.toLocaleString(
                                                'id-ID', {
                                                    style: 'currency',
                                                    currency: 'IDR',
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 0
                                                });
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'top',
                                        formatter: function(value, context) {
                                            return value.toLocaleString('id-ID', {
                                                style: 'currency',
                                                currency: 'IDR',
                                                minimumFractionDigits: 0,
                                                maximumFractionDigits: 0
                                            });
                                        },
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                }
                            },
                            plugins: [ChartDataLabels]
                        });
                    },
                    error: function(response) {
                        console.error("Error:", response);
                        alert("Terjadi kesalahan saat memproses data. Silakan coba lagi.");
                    }
                });
            });
        });
    </script>
@endsection
