@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-dark">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('produks') }}" class="text-dark">List Products</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('keranjang') }}" class="text-dark">Keranjang</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bayar</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                {{-- Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success mt-4">
                        {{ session('success') }}
                    </div>
                @endif
                {{-- Error Message --}}
                @if (session('error'))
                    <div class="alert alert-danger mt-4">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Product Detail --}}
        <div class="row">
            <div class="col">
                <a href="{{ route('keranjang') }}" class="btn btn-sm btn-dark"><i class="fas fa-arrow-left"></i></a>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <h4>Informasi Pembayaran</h4>
                <hr>
                <p>
                    <h2>Jumlah yang Harus dibayar:</h2>
                    <h2><strong> Rp. {{ number_format($total_harga) }}</strong></h2>
                </p>
                <br>
                <div class="media">
                    <img class="mr-3" src="{{ url('assets/bca.png') }}" alt="Bank BCA" width="80">
                    <img class="mr-3" src="{{ url('assets/rp_lembar.png') }}" alt="Cash" width="80">
                </div>
            </div>
            <div class="col">
                <h4>Informasi Pemesan</h4>
                <hr>
                <form method="POST" action="{{ route('bayar') }}">
                    @csrf
                    <div class="form-group">
                        <label for="nomormeja">Nomor Meja</label>
                        <input type="text" name="nomormeja" id="nomormeja" class="form-control"
                            value="{{ $pesanan->no_meja ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="namapemesan">Nama Pemesan</label>
                        <input type="text" name="namapemesan" id="namapemesan" class="form-control"
                            value="{{ $pesanan->nama_pemesan ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="metode_pembayaran">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                            <option value="tunai" {{ ($pesanan->metode_pembayaran ?? '') == 'tunai' ? 'selected' : '' }}>Tunai
                            </option>
                            <option value="bank" {{ ($pesanan->metode_pembayaran ?? '') == 'bank' ? 'selected' : '' }}>Bank
                            </option>
                        </select>
                    </div>
                    <div id="pembayaran_bank" style="display: none;">
                        <div class="form-group" style="text-align: center;">
                            <img src="{{ asset('assets/barcode.png') }}" alt="Barcode" width="200">
                        </div>
                    </div>
                    <div id="pembayaran_tunai">
                        <div class="form-group">
                            <label for="total_bayar">Total Bayar</label>
                            <input type="number" name="total_bayar" id="total_bayar" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-modern btn-block">Bayar</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const metodePembayaran = document.getElementById('metode_pembayaran');
        const pembayaranBank = document.getElementById('pembayaran_bank');
        const pembayaranTunai = document.getElementById('pembayaran_tunai');

        metodePembayaran.addEventListener('change', function () {
            if (this.value === 'bank') {
                pembayaranBank.style.display = 'block';
                pembayaranTunai.style.display = 'none';
            } else {
                pembayaranBank.style.display = 'none';
                pembayaranTunai.style.display = 'block';
            }
        });
        if (metodePembayaran.value === 'bank') {
            pembayaranBank.style.display = 'block';
            pembayaranTunai.style.display = 'none';
        } else {
            pembayaranBank.style.display = 'none';
            pembayaranTunai.style.display = 'block';
        }

        function hapusPesanan(id) {
            console.log('Fungsi hapusPesanan terpanggil');
            document.getElementById('hapusPesananForm' + id).submit();
        }
    </script>
@endsection
