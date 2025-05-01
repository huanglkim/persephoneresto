@extends('layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
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

        .btn {
            border-radius: 8px;
        }

        .btn i {
            margin-right: 4px;
        }

        /* Tambahkan CSS berikut untuk membuat teks di tengah */
        .table th,
        .table td {
            text-align: center;
            vertical-align: middle; /* Jika kamu juga ingin teks berada di tengah secara vertikal */
        }
    </style>
@endsection
@section('content')
<div class="container">
    <h3 class="fw-bold mb-4">Laporan Produk Paling Diminati</h3>

    {{-- Filter --}}
    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="bulan" class="form-label">Bulan:</label>
                    <select name="bulan" id="bulan" class="form-select">
                        @foreach (range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tahun" class="form-label">Tahun:</label>
                    <select name="tahun" id="tahun" class="form-select">
                        @foreach ($tahunList as $t)
                            <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-modern w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="card shadow-sm">
        <div class="card-body">
            @if ($statistiks->isEmpty())
                <div class="alert alert-info text-center">Tidak ada data untuk bulan dan tahun yang dipilih.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0 table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($statistiks as $index => $stat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stat->produk->nama ?? 'Tidak ditemukan' }}</td>
                                    <td>Rp {{ number_format($stat->produk->harga ?? 0, 0, ',', '.') }}</td>
                                    <td><strong>{{ $stat->jumlah_terjual }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    </div>
@endsection