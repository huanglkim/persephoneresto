@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- Breadcrumb --}}
        <div class="row mb-3">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-2 rounded">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none text-dark">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List Products</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Header dan Pencarian --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h3 class="fw-bold">{{ $title }}</h3>
            </div>
            <div class="col-md-4">
                <form action="{{ $menu ? route('produks.menu', $menu->id) : route('produks') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Produk --}}
        <div class="row">
            @forelse ($produks as $produk)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ url('assets/produk/' . $produk->gambar) }}" alt="{{ $produk->nama }}" class="card-img-top" style="object-fit: cover; height: 180px;">
                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $produk->nama }}</h5>
                            <p class="text-muted mb-1">Stok: {{ $produk->stok }}</p>
                            <p class="text-danger fw-bold">Rp. {{ number_format($produk->harga) }}</p>
                            <a href="{{ route('produk.detail', $produk->id) }}" class="btn btn-outline-dark mt-auto">
                                <i class="fas fa-shopping-cart"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-info text-center">
                        Tidak ada produk yang ditemukan.
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="row mt-3">
            <div class="col d-flex justify-content-center">
                {{ $produks->links() }}
            </div>
        </div>

    </div>
@endsection
