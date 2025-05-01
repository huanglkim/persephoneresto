@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="print-hide">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-dark">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('produks') }}" class="text-dark">List Products</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('keranjang') }}" class="text-dark">Keranjang</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bayar Sukses</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 print-hide">
                @if (session('success'))
                    <div class="alert alert-success mt-4">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ asset('assets/slider/logo.png') }}" alt="Logo PersephoneResto" width="100">
                        </div>
                        <h3 class="text-center">ParsheponeResto</h3>
                        <hr>
                        <p><strong>Nota:</strong> {{ $pesanan->kode_pemesanan }}</p>
                        <p><strong>Nomor Meja:</strong> {{ $pesanan->no_meja }}</p>
                        <p><strong>Nama Pemesan:</strong> {{ $pesanan->nama_pemesan }}</p>
                        <p><strong>Nama Kasir:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Tanggal dan Waktu Pemesanan:</strong> <span id="realtime-datetime"></span></p>
                        <hr>
                        <p><strong>Item Pesanan:</strong></p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pesanan_details as $detail)
                                    <tr>
                                        <td>{{ $detail->produk->nama }}</td>
                                        <td>{{ $detail->jumlah_pesanan }}</td>
                                        <td>Rp. {{ number_format($detail->produk->harga, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <p><strong>Total Harga:</strong> Rp. {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                        <p><strong>Total Bayar:</strong> Rp. {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</p>
                        <p><strong>Kembalian:</strong> Rp. {{ number_format($pesanan->kembalian, 0, ',', '.') }}</p>
                        <button class="btn btn-modern mt-3 print-hide" onclick="window.print()">Print</button>
                    </div>
                    <div class="card-footer text-center">
                        Selamat Menikmati, dan Silahkan datang Kembali!
                        <p><strong>Wifi : PersephoneResto | Password : PESANDULU </strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .print-hide {
                display: none;
            }
        }
    </style>
@endsection

@section('script')
    <script>
        function updateDateTime() {
            var now = new Date();
            var year = now.getFullYear();
            var month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-11
            var day = String(now.getDate()).padStart(2, '0');
            var hours = String(now.getHours()).padStart(2, '0');
            var minutes = String(now.getMinutes()).padStart(2, '0');
            var seconds = String(now.getSeconds()).padStart(2, '0');

            var dateTimeString = day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ':' + seconds;
            document.getElementById('realtime-datetime').innerHTML = dateTimeString;
        }

        // Jalankan fungsi updateDateTime setiap detik
        setInterval(updateDateTime, 1000);
    </script>
@endsection
