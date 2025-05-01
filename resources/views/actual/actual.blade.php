@extends('layouts.app')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .card-custom {
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
        }

        .table thead {
            background-color: #f1f1f1;
            color: #343a40;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle; /* Jika kamu juga ingin teks berada di tengah secara vertikal */
        }
        .form-select,
        .form-label,
        .btn {
            margin-right: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .card-header-custom {
            background-color: #ffd6a5;
            border-bottom: 1px solid #eee;
            border-radius: 16px 16px 0 0;
            padding: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('ac.bar') }}" class="btn btn-success">
                        <i class="fas fa-chart-bar me-1"></i> Graph
                    </a>
                    <a href="{{ route('laporan') }}" class="btn btn-success">
                        <i class="fas fa-book me-1"></i> Laporan
                    </a>
                </div>

                <!-- Filter Form in Card -->
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('actual') }}" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Bulan:</label>
                                <select name="bulan" class="form-select">
                                    <option value="">Semua Bulan</option>
                                    @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $b)
                                        <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>{{ $b }}</option> @endforeach
                                </select>
                            </div>
                        
                            <div class="col-md-4">
                                <label class="form-label">Tahun:</label>
                                <select name="tahun" class="form-select">
                                    <option value="">Semua Tahun</option>
                                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                </select>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-modern w-100">
            <i class="fas fa-filter me-1"></i> Filter
        </button>
    </div>
    </form>

    </div>
    </div>

    <!-- Data Table Card -->
    <div class="card card-custom">
        <div class="card-header card-header-custom">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Data Pendapatan per Bulan</h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0 table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>User</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($actuals as $no => $item)
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $item->bulan_nama }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ $item->user_nama }}</td>
                                <td>Rp {{ number_format($item->hasil, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $actuals->links() }}
            </div>
        </div>
    </div>

    </div>
    </div>
    </div>
@endsection
