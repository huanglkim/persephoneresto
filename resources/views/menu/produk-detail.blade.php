@extends('layouts.app')
@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <div class="row mb-2">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-dark">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produks') }}" class="text-dark">List Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product Detail</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Success Message --}}
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Product Detail --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <img src="{{ url('assets/produk/' . $produk->gambar) }}" alt="Produk: {{ $produk->nama }}"
                    class="card-img-top img-fluid">
            </div>
        </div>
        <div class="col-md-6">
            <h3>
                <strong>{{ $produk->nama }}</strong>
                @if ($produk->is_ready == 1)
                <span class="badge badge-success badge-custom"><i class="fas fa-check"></i> Ready</span>
                @else
                <span class="badge badge-danger badge-custom"><i class="fas fa-times"></i> Habis</span>
                @endif
            </h3>
            <h4>Rp. {{ number_format($produk->harga, 0, ',', ',') }}</h4>
            <strong>Stok : {{ $produk->stok }}</strong>

            {{-- Form Add to Cart --}}
            <form action="{{ route('produk.addToCart', $produk->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        <table class="table" style="border-top: hidden">
                            <tr>
                                <td>Menu</td>
                                <td>:</td>
                                <td>{{ optional($produk->menu)->nama }}</td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>:</td>
                                <td>{{ optional($produk->menu)->kategori }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah</td>
                                <td>:</td>
                                <td>
                                    <input id="jumlah_pesanan" type="number"
                                        class="form-control @error('jumlah_pesanan') is-invalid @enderror"
                                        name="jumlah_pesanan" value="{{ old('jumlah_pesanan', 1) }}" required min="1"
                                        autofocus>
                                    @error('jumlah_pesanan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <button type="submit" class="btn btn-dark btn-block"
                                        @if ($produk->is_ready !== 1 || $produk->stok <= 0) disabled @endif>
                                        <i class="fas fa-box"></i> Pesan
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
