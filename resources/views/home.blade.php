@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Banner --}}
    <div class="banner mb-4">
        <img src="{{ url('assets/slider/slider1.png') }}" alt="Banner" class="img-fluid w-100 rounded shadow-sm">
    </div>

    {{-- Pilih Menu --}}
    <div class="pilih-menu mt-4">
        <h3 class="fw-bold">Pilih Menu</h3>
        <div class="row mt-3">
            @foreach ($menus as $menu)
                <div class="col-6 col-md-3 mb-4">
                    <a href="{{ route('produks.menu', $menu->id) }}" class="text-decoration-none">
                        <div class="card shadow-sm h-100">
                            <img src="{{ url('assets/menu/' . $menu->gambar) }}" alt="{{ $menu->nama }}"
                                class="card-img-top" style="height: 160px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title text-dark">{{ $menu->nama }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Best Products --}}
    <div class="products mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">Best Products</h3>
            <a href="{{ route('produks') }}" class="btn btn-dark"><i class="fas fa-eye"></i> Lihat Semua</a>
        </div>
        <div class="row">
            @foreach ($produks as $produk)
                <div class="col-6 col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ url('assets/produk/' . $produk->gambar) }}" alt="{{ $produk->nama }}"
                            class="card-img-top" style="height: 180px; object-fit: cover;">
                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $produk->nama }}</h5>
                            <p class="text-danger fw-semibold">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            <a href="{{ route('produk.detail', $produk->id) }}" class="btn btn-outline-dark mt-auto">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Produk Paling Diminati --}}
    @if (Auth::check() && Auth::user()->name === 'Ale Huang')
    <h3 class="fw-bold mt-5">Produk Paling Diminati Bulan {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}</h3>
    <div class="row mt-4">
        @foreach ($topProduk as $stat)
            @php $produk = $stat->produk; @endphp
            @if ($produk)
                <div class="col-6 col-sm-4 col-md-2 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ url('assets/produk/' . $produk->gambar) }}" class="card-img-top"
                            style="height: 120px; object-fit: cover;">
                        <div class="card-body text-center p-2 d-flex flex-column">
                            <h6 class="fw-bold mb-1" style="font-size: 14px;">{{ $produk->nama }}</h6>
                            <p class="text-danger mb-1" style="font-size: 13px;">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            <p class="text-muted mb-2" style="font-size: 12px;">Terjual {{ $stat->jumlah_terjual }}x</p>
                            <a href="{{ route('produk.detail', $produk->id) }}" class="btn btn-sm btn-outline-dark mt-auto" style="font-size: 12px;">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @endif

</div>
@endsection
