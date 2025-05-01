@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-dark">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produks') }}" class="text-dark">List Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Success / Error Message --}}
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success mt-4">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mt-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Product Detail Table --}}
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Gambar</th>
                            <th>Nama Item</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th><strong>Total Harga</strong></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                            $grand_total = 0;
                        @endphp
                        @forelse ($pesanan_details as $pesanan_detail)
                            @php
                                if ($pesanan_detail->produk_id) {
                                    $nama_item = $pesanan_detail->produk->nama;
                                    $gambar = url('assets/produk/' . $pesanan_detail->produk->gambar);
                                    $harga_satuan = $pesanan_detail->produk->harga;
                                } elseif ($pesanan_detail->topping_id) {
                                    $nama_item = $pesanan_detail->topping->nama;
                                    $gambar = null; // Set gambar ke null
                                    $harga_satuan = $pesanan_detail->topping->harga;
                                } else {
                                    $nama_item = 'Item Tidak Dikenali';
                                    $gambar = null; // Set gambar ke null
                                    $harga_satuan = 0;
                                }
                                $total_item = $harga_satuan * $pesanan_detail->jumlah_pesanan;
                                $grand_total += $total_item;
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    @if ($gambar)
                                        <img src="{{ $gambar }}" class="img-fluid" width="100">
                                    @else
                                        - 
                                    @endif
                                </td>
                                <td>{{ $nama_item }}</td>
                                <td>{{ $pesanan_detail->jumlah_pesanan }}</td>
                                <td>Rp. {{ number_format($harga_satuan, 0, ',', '.') }}</td>
                                <td><strong>Rp. {{ number_format($total_item, 0, ',', '.') }}</strong></td>
                                <td>
                                    {{-- Edit Button --}}
                                    <button type="button" class="btn btn-link text-primary p-0 border-0" data-toggle="modal" data-target="#editModal{{ $pesanan_detail->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('keranjang.hapus', $pesanan_detail->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 border-0">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $pesanan_detail->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $pesanan_detail->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('keranjang.update', $pesanan_detail->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $pesanan_detail->id }}">Edit Pesanan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="jumlah_pesanan">Jumlah</label>
                                                    <input type="number" name="jumlah_pesanan" class="form-control" value="{{ $pesanan_detail->jumlah_pesanan }}" min="1" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-modern">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7">Tidak ada Pesanan</td>
                            </tr>
                        @endforelse

                        {{-- Total Harga dan Checkout --}}
                        @if (isset($pesanan) && $pesanan_details->isNotEmpty() && $pesanan->status != 1)
                            <tr>
                                <td colspan="6" align="right"><strong>Total Harga :</strong></td>
                                <td colspan="2"><strong>Rp. {{ number_format($grand_total, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="6" align="right"><strong>Total Yang Harus Dibayarkan :</strong></td>
                                <td colspan="2"><strong>Rp. {{ number_format($grand_total, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td colspan="2">
                                    <a href="{{ route('checkout') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-arrow-right"></i> Bayar
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

