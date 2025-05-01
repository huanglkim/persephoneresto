@extends('layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fa;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #eaeaea;
        }

        .card-body {
            background-color: #ffffff;
        }

        .table thead {
            background-color: #fcd5ab;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle; /* Jika kamu juga ingin teks berada di tengah secara vertikal */
        }

        .btn {
            border-radius: 8px;
        }

        .btn i {
            margin-right: 4px;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Laporan</h5>
            <div>
                <a href="{{ route('laporan.doughnut', request()->all()) }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-pie"></i> Doughnut
                </a>
                <a href="{{ route('laporan.bar', request()->all()) }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-bar"></i> Bar
                </a>
                <a href="{{ route('laporan.export', request()->all()) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel"></i> Export
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('laporan') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Karyawan</label>
                        <select name="user_id" class="form-control">
                            <option value="">Pilih Karyawan</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->name }}" {{ request('user_id') == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="month" class="form-control">
                            <option value="">Pilih Bulan</option>
                            @foreach ($months as $month)
                                <option value="{{ $month->month }}" {{ request('month') == $month->month ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $month->month, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <select name="year" class="form-control">
                            <option value="">Pilih Tahun</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->year }}" {{ request('year') == $year->year ? 'selected' : '' }}>{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-modern w-100">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Data Pesanan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0 table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataPesanans as $key => $pesanan)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $pesanan->user->name ?? 'Tidak Diketahui' }}</td>
                                <td>{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d M Y') }}</td>
                                <td>Rp. {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('laporan.print', request()->all()) }}" class="btn btn-outline-secondary" target="_blank">
                    <i class="fas fa-print"></i> Print by Filter
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#modalForm').submit(function(event) {
                event.preventDefault();
                let formData = new FormData(this);
                let btn = $(this).find("button[type='submit']");
                btn.prop("disabled", true).text("Importing...");

                $.ajax({
                    url: "{{ route('laporan.import') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.success,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = "Gagal import data.";
                        if (errors) {
                            errorMessage += "<br>";
                            $.each(errors, function(key, value) {
                                errorMessage += value + "<br>";
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            html: errorMessage,
                        });
                    },
                    complete: function() {
                        btn.prop("disabled", false).text("Import");
                    }
                });
            });

            $('#exportBtn').click(function(event) {
                event.preventDefault();
                let params = new URLSearchParams(window.location.search);
                let url = "{{ route('laporan.export') }}?" + params.toString();
                window.location.href = url;
            });

            $('#printInvoice').click(function() {
                let params = new URLSearchParams(window.location.search);
                let url = "{{ route('laporan.print') }}?" + params.toString();
                window.open(url, '_blank');
            });

            $('#doughnutChart, #barChart').click(function(event) {
                event.preventDefault();
                let params = new URLSearchParams(window.location.search);
                let href = $(this).attr('href') + "?" + params.toString();
                window.location.href = href;
            });
        });
    </script>
@endsection