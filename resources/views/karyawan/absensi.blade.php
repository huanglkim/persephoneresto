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
    <div class="absensi mt-5">
        <div class="text-right mb-3">
            <button class="btn btn-modern" data-bs-toggle="modal" data-bs-target="#addAbsensiModal">
                <i class="fas fa-plus"></i> Tambah Absensi
            </button>
        </div>
        <table id="absensiTable" class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Divisi</th>
                    <th>Hari Kerja</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alfa</th>
                    <th>Cuti</th>
                    <th>Potongan Gaji</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absensis as $absensi)
                <tr data-id="{{ $absensi->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $absensi->karyawan ? $absensi->karyawan->nama_karyawan : 'No Karyawan' }}</td>
                    <td>{{ $absensi->jabatan->nama_jabatan }}</td>
                    <td>{{ $absensi->divisi->nama }}</td>
                    <td>{{ $absensi->jumlah_hari_kerja }}</td>
                    <td>{{ $absensi->jumlah_hari_sakit }}</td>
                    <td>{{ $absensi->jumlah_hari_izin }}</td>
                    <td>{{ $absensi->jumlah_hari_alfa }}</td>
                    <td>{{ $absensi->jumlah_hari_cuti }}</td>
                    <td>Rp. {{ number_format($absensi->potongan_gaji_pokok, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn btn-modern btn-edit" data-id="{{ $absensi->id }}" data-bs-toggle="modal" data-bs-target="#editAbsensiModal">
                            <i class="fas fa-edit"></i> 
                        </button>                        
                        <button class="btn btn-delete" data-id="{{ $absensi->id }}">
                            <i class="fas fa-trash"></i> 
                        </button>
                    </td>
                </tr> @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add Absensi -->
<div class="modal
        fade" id="addAbsensiModal" tabindex="-1" aria-labelledby="addAbsensiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAbsensiModalLabel">Tambah Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAbsensiForm">
                <div class="modal-body">
                    <!-- Fields -->
                    <div class="mb-3">
                        <label for="karyawan_id" class="form-label">Nama Karyawan</label>
                        <select class="form-select" id="karyawan_id" name="karyawan_id" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach ($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan_id" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan_id" name="jabatan_id" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="divisi_id" class="form-label">Divisi</label>
                        <input type="text" class="form-control" id="divisi_id" name="divisi_id" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_hari_kerja" class="form-label">Jumlah Hari Kerja</label>
                        <input type="number" class="form-control" id="jumlah_hari_kerja" name="jumlah_hari_kerja" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_hari_sakit" class="form-label">Jumlah Hari Sakit</label>
                        <input type="number" class="form-control" id="jumlah_hari_sakit" name="jumlah_hari_sakit" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_hari_izin" class="form-label">Jumlah Hari Izin</label>
                        <input type="number" class="form-control" id="jumlah_hari_izin" name="jumlah_hari_izin" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_hari_alfa" class="form-label">Jumlah Hari Alfa</label>
                        <input type="number" class="form-control" id="jumlah_hari_alfa" name="jumlah_hari_alfa" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_hari_cuti" class="form-label">Jumlah Hari Cuti</label>
                        <input type="number" class="form-control" id="jumlah_hari_cuti" name="jumlah_hari_cuti" required>
                    </div>
                    <div class="mb-3">
                        <label for="potongan_gaji_pokok" class="form-label">Potongan Gaji Pokok</label>
                        <input type="number" class="form-control" id="potongan_gaji_pokok" name="potongan_gaji_pokok"
                            required>
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

    <!-- Modal Edit Absensi -->
    <div class="modal fade" id="editAbsensiModal" tabindex="-1" aria-labelledby="editAbsensiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editAbsensiForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAbsensiModalLabel">Edit Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_karyawan_id" class="form-label">Nama Karyawan</label>
                            <select class="form-select" id="edit_karyawan_id" name="karyawan_id" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jabatan_id" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="edit_jabatan_id" name="jabatan_id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit_divisi_id" class="form-label">Divisi</label>
                            <input type="text" class="form-control" id="edit_divisi_id" name="divisi_id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jumlah_hari_kerja" class="form-label">Jumlah Hari Kerja</label>
                        <input type="number" class="form-contro" id="edit_jumlah_hari_kerja"
                                name="jumlah_hari_kerja" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jumlah_hari_sakit" class="form-label">Jumlah Hari Sakit</label>
                            <input type="number" class="form-control" id="edit_jumlah_hari_sakit"
                                name="jumlah_hari_sakit" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jumlah_hari_izin" class="form-label">Jumlah Hari Izin</label>
                            <input type="number" class="form-control" id="edit_jumlah_hari_izin"
                                name="jumlah_hari_izin" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jumlah_hari_alfa" class="form-label">Jumlah Hari Alfa</label>
                            <input type="number" class="form-control" id="edit_jumlah_hari_alfa"
                                name="jumlah_hari_alfa" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jumlah_hari_cuti" class="form-label">Jumlah Hari Cuti</label>
                            <input type="number" class="form-control" id="edit_jumlah_hari_cuti"
                                name="jumlah_hari_cuti" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_potongan_gaji_pokok" class="form-label">Potongan Gaji Pokok</label>
                            <input type="number" class="form-control" id="edit_potongan_gaji_pokok"
                                name="potongan_gaji_pokok" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-modern">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Mengupdate Jabatan dan Divisi berdasarkan Karyawan pada modal Tambah
            $('#karyawan_id').on('change', function() {
                var karyawanId = $(this).val();
                if (karyawanId) {
                    $.ajax({
                        url: '{{ url('/getJabatanAndDivisi') }}/' + karyawanId,
                        method: 'GET',
                        success: function(response) {
                            if (response) {
                                $('#jabatan_id').val(response.jabatan_id);
                                $('#divisi_id').val(response.divisi_id);
                                $('#jabatan_id').prop('readonly', true);
                                $('#divisi_id').prop('readonly', true);
                            } else {
                                resetJabatanDivisiAdd();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Terjadi kesalahan saat mengambil data karyawan:",
                                error);
                            resetJabatanDivisiAdd();
                        }
                    });
                } else {
                    resetJabatanDivisiAdd();
                }
            });

            function resetJabatanDivisiAdd() {
                $('#jabatan_id').val('');
                $('#divisi_id').val('');
                $('#jabatan_id').prop('readonly', false);
                $('#divisi_id').prop('readonly', false);
            }

            // Handling form submission for add absensi
            $('#addAbsensiForm').on('submit', function(e) {
                e.preventDefault();
                let data = {
                    karyawan_id: $('#karyawan_id').val(),
                    jabatan_id: $('#jabatan_id').val(),
                    divisi_id: $('#divisi_id').val(),
                    jumlah_hari_kerja: $('#jumlah_hari_kerja').val(),
                    jumlah_hari_sakit: $('#jumlah_hari_sakit').val(),
                    jumlah_hari_izin: $('#jumlah_hari_izin').val(),
                    jumlah_hari_alfa: $('#jumlah_hari_alfa').val(),
                    jumlah_hari_cuti: $('#jumlah_hari_cuti').val(),
                    potongan_gaji_pokok: $('#potongan_gaji_pokok').val(),
                    _token: '{{ csrf_token() }}',
                };

                $.ajax({
                    url: '{{ route('absensi.store') }}',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            $('#addAbsensiModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan, coba lagi.');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = '';
                            for (let field in errors) {
                                errorMessages += errors[field].join(', ') + '\n';
                            }
                            alert('Terjadi kesalahan validasi:\n' + errorMessages);
                        } else {
                            alert('Terjadi kesalahan, coba lagi.');
                        }
                    }
                });
            });

            // Event listener untuk menampilkan modal edit dan mengisi data
            $('.btn-edit').on('click', function() {
                var absensiId = $(this).data('id');
                var modal = $('#editAbsensiModal');

                $.ajax({
                    url: '{{ route('absensi.edit', '') }}/' + absensiId,
                    method: 'GET',
                    success: function(response) {
                        if (response) {
                            modal.find('#edit_id').val(response.id);
                            modal.find('#edit_karyawan_id').val(response.karyawan_id).trigger(
                                'change'); // Trigger change untuk mengisi jabatan & divisi
                            modal.find('#edit_jumlah_hari_kerja').val(response
                                .jumlah_hari_kerja);
                            modal.find('#edit_jumlah_hari_sakit').val(response
                                .jumlah_hari_sakit);
                            modal.find('#edit_jumlah_hari_izin').val(response.jumlah_hari_izin);
                            modal.find('#edit_jumlah_hari_alfa').val(response.jumlah_hari_alfa);
                            modal.find('#edit_jumlah_hari_cuti').val(response.jumlah_hari_cuti);
                            modal.find('#edit_potongan_gaji_pokok').val(response
                                .potongan_gaji_pokok);
                            modal.modal('show'); // Pastikan modal edit ditampilkan
                        } else {
                            alert('Data tidak ditemukan!');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mengambil data absensi.');
                    }
                });
            });

            // Mengupdate Jabatan dan Divisi berdasarkan Karyawan pada modal Edit
            $('#edit_karyawan_id').on('change', function() {
                var karyawanId = $(this).val();
                if (karyawanId) {
                    $.ajax({
                        url: '{{ url('/getJabatanAndDivisi') }}/' + karyawanId,
                        method: 'GET',
                        success: function(response) {
                            if (response) {
                                $('#edit_jabatan_id').val(response.jabatan_id);
                                $('#edit_divisi_id').val(response.divisi_id);
                                $('#edit_jabatan_id').prop('readonly', true);
                                $('#edit_divisi_id').prop('readonly', true);
                            } else {
                                resetJabatanDivisiEdit();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Terjadi kesalahan saat mengambil data karyawan:",
                                error);
                            resetJabatanDivisiEdit();
                        }
                    });
                } else {
                    resetJabatanDivisiEdit();
                }
            });

            function resetJabatanDivisiEdit() {
                $('#edit_jabatan_id').val('');
                $('#edit_divisi_id').val('');
                $('#edit_jabatan_id').prop('readonly', false);
                $('#edit_divisi_id').prop('readonly', false);
            }


            // Handling form submission for edit absensi
            $('#editAbsensiForm').on('submit', function(e) {
                e.preventDefault();
                var absensiId = $('#edit_id').val();
                var data = {
                    karyawan_id: $('#edit_karyawan_id').val(),
                    jabatan_id: $('#edit_jabatan_id').val(),
                    divisi_id: $('#edit_divisi_id').val(),
                    jumlah_hari_kerja: $('#edit_jumlah_hari_kerja').val(),
                    jumlah_hari_sakit: $('#edit_jumlah_hari_sakit').val(),
                    jumlah_hari_izin: $('#edit_jumlah_hari_izin').val(),
                    jumlah_hari_alfa: $('#edit_jumlah_hari_alfa').val(),
                    jumlah_hari_cuti: $('#edit_jumlah_hari_cuti').val(),
                    potongan_gaji_pokok: $('#edit_potongan_gaji_pokok').val(),
                    _token: '{{ csrf_token() }}',
                };

                $.ajax({
                    url: '{{ route('absensi.update', '') }}/' + absensiId,
                    method: 'PUT',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            $('#editAbsensiModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan saat mengupdate, coba lagi.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mengupdate, coba lagi.');
                    }
                });
            });

            $('.btn-delete').on('click', function() {
                var absensiId = $(this).data('id');
                var row = $(this).closest('tr');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data absensi ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('absensi.destroy', '') }}/' + absensiId,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                if (response.success) {
                                    row.fadeOut(500, function() {
                                        $(this).remove();
                                    });
                                    Swal.fire(
                                        'Terhapus!',
                                        'Data absensi berhasil dihapus.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        'Terjadi kesalahan saat menghapus data.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
