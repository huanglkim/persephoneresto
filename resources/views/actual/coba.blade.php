@extends('layouts.app')

@section('title', 'Pendapatan per Bulan')

@section('content')
    <!-- Form Filter -->
    <h1>Filter Pendapatan</h1>

    <form id="filterForm" method="POST" action="{{ route('laporan.bar') }}">
        @csrf
        <label for="bulan_awal">Bulan Awal:</label>
        <input type="month" id="bulan_awal" name="bulan_awal" required>

        <label for="bulan_akhir">Bulan Akhir:</label>
        <input type="month" id="bulan_akhir" name="bulan_akhir" required>

        <button type="submit">Filter</button>
    </form>

    <!-- Tempat untuk menampilkan grafik -->
   <canvas id="pendapatanChart" width="400" height="200"></canvas>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        // Menangani form filter dan request AJAX untuk mendapatkan data pendapatan
        document.getElementById('filterForm').addEventListener('submit', function(event) {
            event.preventDefault(); // mencegah form agar tidak submit secara default

            var data = $('#filterForm').serialize();
            var form = $('#filterForm');
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    //console.log(response);
                    $('#pendapatan').html(response);
                },
                error: function(response) {
                    console.log(response);
                    $('#pendapatan').html(response);
                }
            });

        });
    </script>
@endsection

<div class="chart-container" style="position: relative; width: 100%; max-width: 600px; height: auto;">
    <canvas id="chartbar"></canvas>
    @foreach ($sales as $index => $sale)
    <input type="hidden" class="chartdatabar" data-label-bar="{{ $sale['label']  }}"
    value="{{ json_encode($sale['data']) }}" id="chartbar{{ $index + 1 }}">
    @endforeach
    <input type="hidden" class="chartbulanbar" value='{{ json_encode($bulan) }}' id="bulanbar">
</div>
<script>
    // Function to render a bar chart dynamically
    function renderBarChart(ctx, chartType, inputsClass, labelsId) {
        const inputs = document.querySelectorAll(inputsClass);
        const chartLabels = JSON.parse(document.getElementById(labelsId).value);

        const datasets = [];
        inputs.forEach((input, index) => {
            const data = JSON.parse(input.value);
            const label = input.getAttribute(`data-label-${chartType}`);

            datasets.push({
                label: label,
                data: data,
                borderColor: `rgba(${50 + index * 100}, ${100 + index * 50}, ${150 + index * 25}, 1)`,
                backgroundColor: `rgba(${50 + index * 100}, ${100 + index * 50}, ${150 + index * 25}, 0.3)`,
                borderWidth: 2
            });
        });

        return new Chart(ctx, {
            type: chartType,
            data: {
                labels: chartLabels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true, // Keep chart responsive
                plugins: {
                    legend: {
                        position: 'top'
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
            },
            plugins: [{
                id: 'customIndexPlugin',
                afterDatasetDraw: function(chart) {
                    const ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, datasetIndex) => {
                        dataset.data.forEach((value, index) => {
                            const meta = chart.getDatasetMeta(datasetIndex).data[index];
                            const yPosition = meta.y - 10; // Adjust Y position for label
                            const xPosition = meta.x;
                            ctx.font = '12px Arial';
                            ctx.fillStyle = 'black';
                            ctx.fillText(`${value}`, xPosition, yPosition);
                        });
                    });
                }
            }]
        });
    }

    // Bar Chart
    const ctxBar = document.getElementById('chartbar').getContext('2d');
    renderBarChart(ctxBar, 'bar', '.chartdatabar', 'bulanbar');
</script>

CONTROLLER AWAL
<?php

namespace App\Http\Controllers;

use App\Laporan;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanbarController extends Controller
{
    public function index()
    {
        return view('actual.laporanbar');
    }

    public function filterPendapatan(Request $request)
    {
        // Validasi input bulan dan tahun
        // $validated = $request->validate([
        //     'bulan_awal' => 'required|date_format:m-Y',
        //     'bulan_akhir' => 'required|date_format:m-Y',
        // ]);

        $bulan_awal = Carbon::createFromFormat('Y-m', $request['bulan_awal']);
        $bulan_akhir = Carbon::createFromFormat('Y-m', $request['bulan_akhir']);

        // Ambil laporan berdasarkan filter bulan dan tahun
        $users = Laporan::whereBetween('tanggal', [$bulan_awal->startOfMonth(), $bulan_akhir->endOfMonth()])
            ->groupBy('user_id')
            ->pluck('user_id');

        // Generate array bulan antara tanggal awal dan akhir
        $bulan = [];
        $startDateClone = clone $bulan_awal;
        while ($startDateClone->lte($bulan_akhir)) {
            $bulan[] = $startDateClone->format('F Y');
            $startDateClone->addMonth();
        }

        $arraybulan = [];
        $startDate = Carbon::parse($bulan_awal);
        $endDate = Carbon::parse($bulan_akhir);

        while ($startDate->lte($endDate)) {
            $arraybulan[] = [
                'bulan' => $startDate->format('m'), // Format bulan (misal: '11')
                'tahun' => $startDate->format('Y'), // Format tahun (misal: '2024')
            ];
            $startDate->addMonth(); // Tambah 1 bulan
        }
        $sales = [];
        foreach ($users as $iduser) {
            $username = User::where('id', $iduser)->value('name');
            $datapd = [];
            foreach ($arraybulan as $periode) {
                $pd = Laporan::where('user_id', $iduser)
                    ->whereMonth('tanggal', $periode['bulan']) // Ambil bulan yang benar
                    ->whereYear('tanggal', $periode['tahun']) // Ambil tahun yang benar
                    ->sum('total_pendapatan');
                if($pd) {
                    $datapd[] = $pd;
                } else {
                    $datapd[] = 1;
                }
            }
            $sales[] = [
                'label' => $username,
                'data' => $datapd,
            ];
        }
       dd($sales, $bulan);
        return view('actual.barchart', compact(['sales', 'bulan']));

    }
}


BLADE YANG DIJADIKAN SATU
@extends('layouts.app')

@section('title', 'Pendapatan per Bulan')

@section('content')
    <h1>Filter Pendapatan</h1>

    <form id="filterForm" method="POST" action="{{ route('laporan.bar') }}">
        @csrf
        <label for="bulan_awal">Bulan Awal:</label>
        <input type="month" id="bulan_awal" name="bulan_awal" required>

        <label for="bulan_akhir">Bulan Akhir:</label>
        <input type="month" id="bulan_akhir" name="bulan_akhir" required>

        <button type="submit">Filter</button>
    </form>

    <canvas id="pendapatan" width="400" height="200"></canvas>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> <--- Tambahkan ini
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

                        for (const user in response.data[labels[0]]) {
                            const data = labels.map(bulan => response.data[bulan][user] ?? 0);
                            datasets.push({
                                label: user,
                                data: data,
                                // ... konfigurasi dataset lainnya (warna, dll.)
                            });
                        }

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: datasets
                            },
                            options: {
                                plugins: { // <-- Konfigurasi label data di sini
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'center',
                                        formatter: function(value, context) {
                                            return value.toLocaleString(); // Format angka
                                        },
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                }
                            },
                            plugins: [ChartDataLabels] // <-- Daftarkan pluginnya di sini
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
@endsection@extends('layouts.app')

@section('title', 'Pendapatan per Bulan')

@section('content')
    <h1>Filter Pendapatan</h1>

    <form id="filterForm" method="POST" action="{{ route('laporan.bar') }}">
        @csrf
        <label for="bulan_awal">Bulan Awal:</label>
        <input type="month" id="bulan_awal" name="bulan_awal" required>

        <label for="bulan_akhir">Bulan Akhir:</label>
        <input type="month" id="bulan_akhir" name="bulan_akhir" required>

        <button type="submit">Filter</button>
    </form>

    <canvas id="pendapatan" width="400" height="200"></canvas>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> <--- Tambahkan ini
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

                        for (const user in response.data[labels[0]]) {
                            const data = labels.map(bulan => response.data[bulan][user] ?? 0);
                            datasets.push({
                                label: user,
                                data: data,
                                // ... konfigurasi dataset lainnya (warna, dll.)
                            });
                        }

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: datasets
                            },
                            options: {
                                plugins: { // <-- Konfigurasi label data di sini
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'center',
                                        formatter: function(value, context) {
                                            return value.toLocaleString(); // Format angka
                                        },
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                }
                            },
                            plugins: [ChartDataLabels] // <-- Daftarkan pluginnya di sini
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

@extends('layouts.app')

@section('title', 'Pendapatan per Bulan')

@section('content')
    <h1>Filter Pendapatan</h1>

    <form id="filterForm" method="POST" action="{{ route('laporan.bar') }}">
        @csrf
        <label for="bulan_awal">Bulan Awal:</label>
        <input type="month" id="bulan_awal" name="bulan_awal" required>

        <label for="bulan_akhir">Bulan Akhir:</label>
        <input type="month" id="bulan_akhir" name="bulan_akhir" required>

        <button type="submit">Filter</button>
    </form>

    <canvas id="pendapatan" width="400" height="200"></canvas>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> <--- Tambahkan ini
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

                        for (const user in response.data[labels[0]]) {
                            const data = labels.map(bulan => response.data[bulan][user] ?? 0);
                            datasets.push({
                                label: user,
                                data: data,
                                // ... konfigurasi dataset lainnya (warna, dll.)
                            });
                        }

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: datasets
                            },
                            options: {
                                plugins: { // <-- Konfigurasi label data di sini
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'center',
                                        formatter: function(value, context) {
                                            return value.toLocaleString(); // Format angka
                                        },
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                }
                            },
                            plugins: [ChartDataLabels] // <-- Daftarkan pluginnya di sini
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