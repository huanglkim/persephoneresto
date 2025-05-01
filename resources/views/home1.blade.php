@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- {{-- Banner
        <div class="banner">
            <img src="{{ url('assets/slider/slider1.png') }}" alt="Banner" class="img-fluid w-100">
        </div> --}} -->

        {{-- Pilih Menu --}}
        <div class="pilih-menu mt-4">
            <h3><strong>Pilih Menu</strong></h3>
            <div class="row mt-4">
                @foreach ($menus as $menu)
                    <div class="col-md-3 mb-4">
                        <a href="{{ route('produks.menu', $menu->id) }}">
                            <div class="card shadow">
                                <img src="{{ url('assets/menu/' . $menu->gambar) }}" alt="Menu: {{ $menu->nama }}"
                                     class="card-img-top img-fluid">
                                <div class="card-body">
                                    <h3 class="card-title text-center text-dark">{{ $menu->nama }}</h3>
                                    {{-- <p class="card-text">{{ $menu->deskripsi ?? 'Deskripsi tidak tersedia' }}</p> --}}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Best Products --}}
        <div class="products mt-5 mb-5">
            <h3>
                <strong>Best Products</strong>
                <a href="{{ route('produks') }}" class="btn btn-dark float-right"><i class="fas fa-eye"></i> Lihat Semua</a>
            </h3>
            <div class="row mt-4">
                @foreach ($produks as $produk)
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="{{ url('assets/produk/' . $produk->gambar) }}" alt="produk: {{ $produk->nama }}"
                                 class="card-img-top img-fluid">
                            <div class="card-body text-center">
                                <h5 class="card-title"><strong>{{ $produk->nama }}</strong></h5>
                                <h5 class="card-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</h5>
                                <a href="{{ route('produk.detail', $produk->id) }}" class="btn btn-dark btn-block">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>        
    </div>
@endsection
