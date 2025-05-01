@extends('layouts.app')
@section('title', 'Coffeeshop')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            height: auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #doughnut-chart {
            max-width: 100%;
            max-height: 100%;
        }

        #filter-form {
            margin-bottom: 20px;
        }

        .card-body>.chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card mb-4"> {{-- Added margin-bottom --}}
                        <div class="card-header">
                            <h4>Filter</h4>
                        </div>
                        <div class="card-body">
                            <form id="filter-form" method="GET" action="{{ route('laporan.doughnut') }}">
                                @include('layouts.filterbulan_tahun')
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Grafik Pendapatan per User</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="doughnut-chart"></canvas>
                                @foreach ($labels as $index => $label)
                                    <input type="hidden" class="chartdatadonat" data-label-doughnut="{{ $label }}"
                                        value="{{ $data[$index] }}" id="chartdonut{{ $index + 1 }}">
                                @endforeach
                                <input type="hidden" class="chartbulan" value='{{ json_encode($labels) }}' id="bulan">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        function renderDoughnutChart(ctx, chartType, inputsClass, labelsId) {
            const inputs = document.querySelectorAll(inputsClass);
            const chartLabels = JSON.parse(document.getElementById(labelsId).value);

            const data = [];
            const backgroundColor = [];
            const coralShades = [
                'rgba(255, 165, 0, 0.8)',
                'rgba(255, 99, 71, 0.8)',
                'rgba(255, 127, 80, 0.8)',
                'rgba(240, 128, 128, 0.8)',
                'rgba(205, 92, 92, 0.8)',
                'rgba(255, 69, 0, 0.8)',
            ];

            inputs.forEach((input, index) => {
                const value = JSON.parse(input.value);
                data.push(value);
                backgroundColor.push(coralShades[index % coralShades.length]);
            });

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColor,
                        borderWidth: 4,
                        borderColor: `rgba(255, 255, 255, 0.8)`,
                        hoverOffset: 10,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: 60,
                    aspectRatio: 1,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': Rp. ' + tooltipItem.raw.toLocaleString();
                                }
                            }
                        },
                        datalabels: {
                            color: '#000',
                            anchor: 'center',
                            align: 'center',
                            formatter: function(value) {
                                return 'Rp. ' + value.toLocaleString();
                            },
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                    }
                },
                plugins: [ChartDataLabels]
            });
        }

        const ctxDonut = document.getElementById('doughnut-chart').getContext('2d');
        renderDoughnutChart(ctxDonut, 'doughnut', '.chartdatadonat', 'bulan');
    </script>
@endsection
