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
