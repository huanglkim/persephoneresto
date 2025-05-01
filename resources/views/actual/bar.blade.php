@extends('layouts.app')
@section('css')
<style>
  .chart-container {
    width: 100% !important;
    min-height: 350px;
    height: auto;
    margin-top: 20px; /* Add some margin above the chart */
    border: 1px solid #e4e6ef; /* Add a border */
    border-radius: 8px; /* Add rounded corners */
    padding: 20px; /* Add padding inside the chart container */
    background-color: #ffffff; /* Ensure the background is white or a light color */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); /* Add a subtle shadow */
  }

  .card-title {
    font-size: 1.5rem; /* Increase font size for the title */
    margin-bottom: 20px; /* Add space below the title */
    color: #2c3e50; /* Darker color for the title */
  }

  #filter-form {
    margin-bottom: 20px; /* Add space below the form */
  }

  .form-group {
    margin-right: 15px; /* Add horizontal space between form elements */
  }

  .btn-primary {
    margin-top: 0; /* Adjust button margin if needed */
  }
  .card {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
</style>
@endsection
@section('content')
  <div class="section-body">
    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
        <h3 class="card-title">Pendapatan per Hari</h3>
        <form id="filter-form" method="GET" action="{{ route('ac.bar') }}">
          @include('layouts.filterbulan_tahun')
        </form>
        <div class="chart-container">
          <canvas id="chartbar"></canvas>
        </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const chartLabels = JSON.parse(JSON.stringify(@json($datesInMonth)));
  const chartData = @json($chartData);
  const users = @json($users);
  const datasets = [];

  const colors = [
    'rgba(54, 162, 235, 0.8)',  // Blue
    'rgba(255, 99, 132, 0.8)',  // Red
    'rgba(255, 206, 86, 0.8)',  // Yellow
    'rgba(75, 192, 192, 0.8)',  // Green
    'rgba(153, 102, 255, 0.8)', // Purple
    'rgba(255, 159, 64, 0.8)'   // Orange
    // Tambahkan warna lain jika ada lebih dari 6 user
  ];

  const backgroundColors = [
    'rgba(54, 162, 235, 0.5)',
    'rgba(255, 99, 132, 0.5)',
    'rgba(255, 206, 86, 0.5)',
    'rgba(75, 192, 192, 0.5)',
    'rgba(153, 102, 255, 0.5)',
    'rgba(255, 159, 64, 0.5)'
    // Tambahkan warna background lain jika ada lebih dari 6 user
  ];

  users.forEach((user, index) => {
    datasets.push({
      label: user,
      data: chartData[user],
      borderColor: colors[index % colors.length], // Gunakan warna dari array
      backgroundColor: backgroundColors[index % backgroundColors.length],
      borderWidth: 2, // Increased border width for better visibility
      barPercentage: 0.7,
      categoryPercentage: 0.6,
    });
  });

  const ctxBar = document.getElementById('chartbar').getContext('2d');
  const barChart = new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: chartLabels,
      datasets: datasets
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
           labels: {
                            font: {
                                size: 12  // Ukuran font legend
                            }
                        }
        },
      },
      scales: {
        x: {
          ticks: {
            font: {
              size: 12, // Increased font size for x-axis labels
            },
             autoSkip: false, // Prevents label skipping
                        maxRotation: 0, // mencoba untuk membuat label horizontal
                        minRotation: 0
          },
          grid: {
            display: false,
          },
        },
        y: {
          beginAtZero: true,
          ticks: {
            font: {
              size: 12, // Increased font size for y-axis labels
            },
             callback: function(value) {
                            return value.toLocaleString();  // Convert to string with commas
                        }
          },
          grid: {
             color: 'rgba(0, 0, 0, 0.1)', // Light gray grid lines
                        borderDash: [2, 2], // Dashed grid lines
          }
        },
      },
      elements: {
        bar: {
          borderRadius: 8, // Increased border radius for bars
        },
      },
       tooltips: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y.toLocaleString(); // Format the value with commas
                            }
                            return label;
                        }
                    }
                }
    },
    plugins: [{
      id: 'customIndexPlugin',
      afterDatasetDraw: function(chart) {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, datasetIndex) => {
          dataset.data.forEach((value, index) => {
            const meta = chart.getDatasetMeta(datasetIndex).data[index];
            if(meta && meta.x && meta.y){
                const yPosition = meta.y - 15;
                const xPosition = meta.x;
                ctx.font = '12px Arial'; // Increased font size for labels inside bars
                ctx.fillStyle = 'black';
                ctx.textAlign = 'center';
                ctx.fillText(value.toLocaleString(), xPosition, yPosition); // Format the value with commas
            }

          });
        });
      },
    }],
  });
</script>
@endsection
