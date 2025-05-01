@extends('layouts.app')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Edit Produk</h4></div>
                    <div class="card-body">
                        <form action="{{ route('produks.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Item @error('nama') <span class="text-danger">{{ $message }}</span> @enderror</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $produk->nama }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Stok @error('stok') <span class="text-danger">{{ $message }}</span> @enderror</label>
                                        <input type="number" name="stok" class="form-control" value="{{ $produk->stok }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Harga @error('harga') <span class="text-danger">{{ $message }}</span> @enderror</label>
                                        <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Menu @error('menu_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <select name="menu_id" class="form-control">
                                            @foreach (\App\Menu::all() as $menuItem)
                                                <option value="{{ $menuItem->id }}" {{ $produk->menu_id == $menuItem->id ? 'selected' : '' }}>
                                                    {{ $menuItem->nama }}
                                                </option>
                                            @endforeach
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ready? @error('is_ready') <span class="text-danger">{{ $message }}</span> @enderror</label>
                                        <select name="is_ready" class="form-control">
                                            <option value="1" {{ $produk->is_ready ? 'selected' : '' }}>Ya</option>
                                            <option value="0" {{ !$produk->is_ready ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis 
                                            @error('jenis')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <select name="jenis" class="form-control">
                                            <option value="Reguler" {{ $produk->jenis == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                            <option value="Special" {{ $produk->jenis == 'Special' ? 'selected' : '' }}>Special</option>
                                        </select>
                                    </div>
                                </div>                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Porsi @error('porsi') <span class="text-danger">{{ $message }}</span> @enderror</label>
                                        <input type="number" name="porsi" class="form-control" value="{{ $produk->porsi }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gambar @error('gambar') <span class="text-danger">{{ $message }}</span> @enderror</label>
                                        <input type="file" name="gambar" class="form-control">
                                        @if ($produk->gambar)
                                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                                            <img src="{{ asset('assets/produk/' . $produk->gambar) }}" alt="{{ $produk->nama }}" width="100" class="mt-2">
                                        @else
                                            <small class="form-text text-muted">Tidak ada gambar.</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-modern">Simpan Perubahan</button>
                                <button class="btn btn-secondary" type="button" onclick="goBack()">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const updateButton = document.getElementById("updateButton");
            if (updateButton) {
                updateButton.addEventListener("click", function(event) {
                    event.preventDefault();
                    const form = this.form;

                    Swal.fire({
                        title: 'Apakah kamu yakin ingin menyimpan perubahan ini?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

            // Fungsi back juga tetap jalan
            window.goBack = function () {
                window.history.back();
            };
        });
    </script>
@endsection
