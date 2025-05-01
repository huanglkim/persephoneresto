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
    <div class="container">
        {{-- Divisi --}}
        <div class="divisi mt-5">
            <div class="text-right mb-3">
                <button class="btn btn-modern" data-bs-toggle="modal" data-bs-target="#addDivisiModal">
                    <i class="fas fa-plus"></i> Tambah Divisi
                </button>
            </div>

            {{-- Table Divisi --}}
            <table id="divisiTable" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Divisi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($divisis as $divisi)
                        <tr data-id="{{ $divisi->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $divisi->nama }}</td>
                            <td>
                                <button class="btn btn-modern btn-edit" data-id="{{ $divisi->id }}" data-bs-toggle="modal" data-bs-target="#editDivisiModal">
                                    <i class="fas fa-edit"></i> 
                                </button>
                                <button class="btn btn-lilac btn-delete" data-id="{{ $divisi->id }}">
                                    <i class="fas fa-trash"></i> 
                                </button>
                            </td>
                        </tr> @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Divisi Modal --}}
    <div class="modal
        fade" id="addDivisiModal" tabindex="-1" aria-labelledby="addDivisiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDivisiModalLabel">Tambah Divisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDivisiForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Divisi</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lilac" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-modern">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    {{-- Edit Divisi Modal --}}
    <div class="modal fade" id="editDivisiModal" tabindex="-1" aria-labelledby="editDivisiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDivisiModalLabel">Edit Divisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDivisiForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Divisi</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-lilac" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-modern">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.js"></script>

    <script>
        // Handle add divisi form submit
        $('#addDivisiForm').on('submit', function(e) {
            e.preventDefault();
    
            let nama = $('#nama').val();
    
            $.ajax({
                url: '{{ route('divisi.store') }}',
                method: 'POST',
                data: {
                    nama: nama,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        $('#addDivisiModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.pesan || 'Divisi berhasil ditambahkan!',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.pesan || 'Terjadi kesalahan, coba lagi.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan, coba lagi.'
                    });
                }
            });
        });
    
        // Handle edit divisi
        $('.btn-edit').on('click', function() {
            let divisiId = $(this).data('id');
            $.get('{{ url('divisi/edit') }}/' + divisiId, function(response) {
                if (response) {
                    $('#edit_id').val(response.id);
                    $('#edit_nama').val(response.nama);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Data divisi tidak ditemukan.'
                    });
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal mengambil data divisi.'
                });
            });
        });
    
        // Handle edit divisi form submit
        $('#editDivisiForm').on('submit', function(e) {
            e.preventDefault();
    
            let divisiId = $('#edit_id').val();
            let nama = $('#edit_nama').val();
    
            $.ajax({
                url: '{{ route('divisi.store') }}',
                method: 'POST',
                data: {
                    id: divisiId,
                    nama: nama,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        $('#editDivisiModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.pesan || 'Divisi berhasil diperbarui!',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.pesan || 'Terjadi kesalahan, coba lagi.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan, coba lagi.'
                    });
                }
            });
        });
    
        // Handle delete divisi
        $('.btn-delete').on('click', function() {
            let divisiId = $(this).data('id');
    
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Divisi ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('divisi/destroy') }}/' + divisiId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                $('tr[data-id="' + divisiId + '"]').remove();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dihapus',
                                    text: response.pesan || 'Divisi berhasil dihapus!',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.pesan || 'Gagal menghapus divisi.'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Terjadi kesalahan, coba lagi.'
                            });
                        }
                    });
                }
            });
        });
    </script>
    
@endsection
