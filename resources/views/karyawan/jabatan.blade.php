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
        <div class="jabatan mt-5">
            <div class="text-right mb-3">
                <button class="btn btn-modern" data-bs-toggle="modal" data-bs-target="#addJabatanModal">
                    <i class="fas fa-plus"></i> Tambah Jabatan
                </button>
            </div>

            <table id="jabatanTable" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Nama Jabatan</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan jabatan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jabatans as $jabatan)
                        <tr data-id="{{ $jabatan->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jabatan->nama_jabatan }}</td>
                            <td>Rp {{ number_format($jabatan->gaji_pokok, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($jabatan->tunjangan_jabatan, 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-modern btn-edit" data-id="{{ $jabatan->id }}" data-bs-toggle="modal" data-bs-target="#editJabatanModal">
                                    <i class="fas fa-edit"></i> 
                                </button>
                                <button class="btn btn-lilac btn-delete" data-id="{{ $jabatan->id }}">
                                    <i class="fas fa-trash"></i> 
                                </button>
                            </td>
                        </tr> @endforeach
                </tbody>
            </table>
        </div>
    </div>
 
    <div class="modal
        fade" id="addJabatanModal" tabindex="-1" aria-labelledby="addJabatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addJabatanModalLabel">Tambah Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addJabatanForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_jabatan">Nama Jabatan</label>
                        <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="gaji_pokok">Gaji Pokok</label>
                        <input type="text" class="form-control" id="gaji_pokok" name="gaji_pokok" required>
                    </div>
                    <div class="mb-3">
                        <label for="tunjangan_jabatan">Tunjangan Jabatan</label>
                        <input type="text" class="form-control" id="tunjangan_jabatan" name="tunjangan_jabatan" required>
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

    {{-- Modal for Adding and Editing Data --}}
    <div class="modal fade" id="editJabatanModal" tabindex="-1" aria-labelledby="editJabatanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editJabatanModalLabel">Edit Jabatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editJabatanForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_nama_jabatan">Nama Jabatan</label>
                            <input type="text" class="form-control" id="edit_nama_jabatan" name="edit_nama_jabatan"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_gaji_pokok">Gaji Pokok</label>
                            <input type="text" class="form-control" id="edit_gaji_pokok" name="edit_gaji_pokok" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tunjangan_jabatan">Tunjangan Jabatan</label>
                            <input type="text" class="form-control" id="edit_tunjangan_jabatan"
                                name="edit_tunjangan_jabatan" required>
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

<script>
    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: message,
            timer: 2500,
            showConfirmButton: false
        });
    }

    // Handle add jabatan form submit
    $('#addJabatanForm').on('submit', function(e) {
        e.preventDefault();

        let nama_jabatan = $('#nama_jabatan').val();
        let gaji_pokok = $('#gaji_pokok').val();
        let tunjangan_jabatan = $('#tunjangan_jabatan').val();

        $.ajax({
            url: '{{ route('jabatan.store') }}',
            method: 'POST',
            data: {
                nama_jabatan: nama_jabatan,
                gaji_pokok: gaji_pokok,
                tunjangan_jabatan: tunjangan_jabatan,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.success) {
                    $('#addJabatanModal').modal('hide');
                    showSuccess('Jabatan berhasil ditambahkan!');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showError('Terjadi kesalahan, coba lagi.');
                }
            },
            error: function() {
                showError('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });

    // Handle edit jabatan
    $('.btn-edit').on('click', function() {
        let jabatanId = $(this).data('id');
        $.get('{{ url('jabatan/edit') }}/' + jabatanId, function(response) {
            if (response) {
                $('#edit_id').val(response.id);
                $('#edit_nama_jabatan').val(response.nama_jabatan);
                $('#edit_gaji_pokok').val(response.gaji_pokok);
                $('#edit_tunjangan_jabatan').val(response.tunjangan_jabatan);
            }
        });
    });

    // Handle edit jabatan form submit
    $('#editJabatanForm').on('submit', function(e) {
        e.preventDefault();

        let jabatanId = $('#edit_id').val();
        let nama_jabatan = $('#edit_nama_jabatan').val();
        let gaji_pokok = $('#edit_gaji_pokok').val();
        let tunjangan_jabatan = $('#edit_tunjangan_jabatan').val();

        $.ajax({
            url: '{{ route('jabatan.store') }}',
            method: 'POST',
            data: {
                id: jabatanId,
                nama_jabatan: nama_jabatan,
                gaji_pokok: gaji_pokok,
                tunjangan_jabatan: tunjangan_jabatan,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.success) {
                    $('#editJabatanModal').modal('hide');
                    showSuccess('Jabatan berhasil diperbarui!');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showError('Gagal memperbarui jabatan.');
                }
            },
            error: function() {
                showError('Terjadi kesalahan saat memperbarui data.');
            }
        });
    });

    // Handle delete jabatan
    $('.btn-delete').on('click', function() {
        let jabatanId = $(this).data('id');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data jabatan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('jabatan/destroy') }}/' + jabatanId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            $('tr[data-id="' + jabatanId + '"]').remove();
                            showSuccess('Jabatan berhasil dihapus!');
                        } else {
                            showError('Gagal menghapus jabatan.');
                        }
                    },
                    error: function() {
                        showError('Terjadi kesalahan saat menghapus.');
                    }
                });
            }
        });
    });
</script>

@endsection
