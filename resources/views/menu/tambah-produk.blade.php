@extends('layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
     body {
    font-family: 'Arial', sans-serif;
    background-color: #fef9f4;
    margin: 20px; /* Tambahkan sedikit margin di sekitar body */
}

.table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    background-color: #fffdf9;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px; /* Tambahkan margin bawah untuk memisahkan dari elemen lain */
}

.table th, .table td {
    padding: 12px; /* Sedikit kurangi padding untuk tampilan yang lebih ringkas */
    text-align: center;
    vertical-align: middle;
    white-space: nowrap; /* Mencegah teks agar tidak wrap (mungkin perlu disesuaikan) */
}

.table thead {
    background-color: #ffd6a5;
    color: #4a3b2c;
}

.table tbody tr:hover {
    background-color: #fff1e6;
    cursor: pointer;
}

.btn {
    border-radius: 8px;
    padding: 8px 12px; /* Sedikit kurangi padding horizontal */
    transition: all 0.3s ease;
    cursor: pointer; /* Tambahkan cursor pointer untuk indikasi interaktif */
    font-size: 0.9em; /* Sedikit perkecil ukuran font tombol */
}

.btn:hover {
    opacity: 0.9;
}

.btn-edit {
    background-color: #ffb347;
    border: none;
    color: white;
}

.btn-edit:hover {
    background-color: #ffa534;
}

.btn-delete {
    background-color: #d96c75;
    border: none;
    color: white;
}

.btn-delete:hover {
    background-color: #c94b59;
}

.alert {
    margin-bottom: 20px;
    border-radius: 8px;
    padding: 10px; /* Tambahkan padding untuk alert */
}

/* Media query untuk tampilan mobile */
@media (max-width: 768px) {
    .table {
        border: none; /* Hilangkan border keseluruhan tabel di mobile */
        box-shadow: none; /* Hilangkan box shadow di mobile */
    }

    .table thead {
        display: none; /* Sembunyikan header tabel */
    }

    .table tbody,
    .table tr,
    .table td {
        display: block; /* Jadikan semua elemen blok */
        width: 100%;
    }

    .table tr {
        margin-bottom: 15px; /* Berikan jarak antar "baris" */
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    .table td {
        padding-left: 50%; /* Beri ruang untuk label */
        position: relative;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .table td:last-child {
        border-bottom: none;
        padding-bottom: 16px; /* Tambahkan padding bawah untuk tombol di baris terakhir */
    }

    .table td::before {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        font-weight: bold;
        content: attr(data-label); /* Ambil teks dari atribut data-label */
        font-size: 0.9em; /* Sesuaikan ukuran font label */
        color: #777; /* Tambahkan warna untuk label */
    }

    /* Tata letak tombol di mobile */
    .table td .btn {
        display: inline-block;
        margin-right: 5px;
        margin-top: 5px;
    }
}
    </style>
@endsection

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Produk Baru</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('produks.simpan') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Item @error('nama')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <input type="text" name="nama" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Stok @error('stok')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <input type="number" name="stok" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Harga @error('harga')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <input type="number" name="harga" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Menu @error('menu_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <select name="menu_id" class="form-control">
                                            <option value="">Pilih Menu</option>
                                            @foreach (\App\Menu::all() as $menuItem)
                                                <option value="{{ $menuItem->id }}">{{ $menuItem->nama }}</option> @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
    <div class="form-group">
        <label>Ready? @error('is_ready')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </label>
        <select name="is_ready" class="form-control">
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
        </select>
    </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Jenis @error('jenis')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
            <select name="jenis" class="form-control">
                <option value="Reguler">Reguler</option>
                <option value="Special">Special</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Porsi @error('porsi')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
            <input type="number" name="porsi" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Gambar @error('gambar')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
            <input type="file" name="gambar" class="form-control">
        </div>
    </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-modern" id="simpanButton">Simpan</button>
        <button class="btn btn-secondary" type="button" onclick="goBack()">Batal</button>
    </div>
    </form>
    </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-4">{{ session('success') }}</div>
    @endif

    <div class="card mt-4">
        <div class="card-header">
            <h4>Daftar Produk</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Menu</th>
                            <th>Ready?</th>
                            <th>Jenis</th>
                            <th>Porsi</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($semuaProduk as $produk)
                            <tr>
                                <td>{{ $produk->nama }}</td>
                                <td>{{ $produk->stok }}</td>
                                <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                <td>{{ $produk->menu->nama }}</td>
                                <td>{{ $produk->is_ready ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $produk->jenis }}</td>
                                <td>{{ $produk->porsi }}</td>
                                <td>
                                    @if ($produk->gambar)
                                        <img src="{{ asset('assets/produk/' . $produk->gambar) }}"
                                            alt="{{ $produk->nama }}" width="50">
                                    @else
                                        Tidak Ada Gambar
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('produks.edit', $produk->id) }}" class="btn btn-sm btn-modern"><i
                                            class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-secondary" onclick="hapusProduk({{ $produk->id }})"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada Produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("simpanButton").addEventListener("click", function(event) {
            event.preventDefault();
            const form = this.form;

            Swal.fire({
                title: 'Apakah kamu yakin ingin menyimpan ini?',
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

        function goBack() {
            window.history.back();
        }

        function hapusProduk(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data produk ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/produks/delete/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Berhasil!', 'Data produk berhasil dihapus.', 'success')
                                    .then(() => window.location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat menghapus data.',
                                    'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menghubungi server.', 'error');
                        });
                }
            });
        }
    </script>
@endsection
