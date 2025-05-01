@extends('layouts.app')
@section('title', 'Coffeeshop')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Basic Styling */
        #gaji-table th,
        #gaji-table td {
            vertical-align: middle;
            /* Teks sejajar di tengah secara vertikal */
        }

        #gaji-table td:nth-child(2),
        #gaji-table td:nth-child(3) {
            text-align: center;
            /* Kode Item & Nama Item rata kiri */
        }

        #gaji-table td:nth-child(1),
        #gaji-table td:nth-child(4) {
            text-align: center;
            /* No & Action rata tengah */
        }
    </style>
@endsection
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <button class="btn btn-icon icon-left btn-" data-toggle="modal" data-target="#addDataModal"
                    onclick="resetForm()">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
                <hr>

                <table class="table table-striped table-bordered table-sm text-center" id="gaji-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Jumlah Hari Kerja</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan Jabatan</th>
                            <th>Total Gaji</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    {{-- Modal for Adding and Editing Data --}}
    <div class="modal fade" id="addDataModal" tabindex="-1" role="dialog" aria-labelledby="addDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDataModalLabel">Tambah Data</h5>
                </div>
                <form id="modalForm" action="{{ route('gaji.store', $gaji->id ?? '') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="karyawan_id">Nama Karyawan</label>
                            <select class="form-control" id="karyawan_id" name="karyawan_id" required>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jabatan_id">Jabatan</label>
                            <select class="form-control" id="jabatan_id" name="jabatan_id" required>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="absensi_id">Absensi</label>
                            <select class="form-control" id="absensi_id" name="absensi_id" required>
                                @foreach ($absensis as $absensi)
                                    <option value="{{ $absensi->id }}">{{ $absensi->jumlah_hari_kerja }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gaji_pokok">Gaji Pokok</label> {{-- Corrected label --}}
                            <select class="form-control" id="gaji_pokok" name="gaji_pokok" required> {{-- Added name --}}
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->gaji_pokok }}">
                                        {{ 'Rp. ' . number_format($jabatan->gaji_pokok, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tunjangan_jabatan">Tunjangan</label> {{-- Corrected label --}}
                            <select class="form-control" id="tunjangan_jabatan" name="tunjangan_jabatan" required>
                                {{-- Added name --}}
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->tunjangan_jabatan }}">
                                        {{ 'Rp. ' . number_format($jabatan->tunjangan_jabatan, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="total_gaji">Total Gaji</label>
                            <select class="form-control" id="total_gaji" name="total_gaji" required>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->tunjangan_jabatan + $jabatan->gaji_pokok }}">
                                        {{ 'Rp. ' . number_format($jabatan->tunjangan_jabatan + $jabatan->gaji_pokok, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('gaji') }}" class="btn btn-danger">Close</a>
                        <button type="button" class="btn btn-modern" onclick="confirmSave()">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ asset('assets/modules/sweetalert2/sweetalert2@11.js') }}"></script>

    <script>
        $(document).ready(function() {
            let table = $('#gaji-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('gaji.data') }}",
                    type: "GET",
                    error: function(xhr, error, thrown) {
                        console.error("AJAX Error: ", error, thrown);
                        Swal.fire("Error", "Failed to load data!", "error");
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_karyawan',
                        name: 'karyawan.nama_karyawan'
                    },
                    {
                        data: 'nama_jabatan',
                        name: 'jabatan.nama_jabatan'
                    },
                    {
                        data: 'jumlah_hari_kerja',
                        name: 'absensi.jumlah_hari_kerja'
                    },
                    {
                        data: 'gaji_pokok',
                        name: 'jabatan.gaji_pokok'
                    },
                    {
                        data: 'tunjangan_jabatan',
                        name: 'jabatan.tunjangan_jabatan'
                    },
                    {
                        data: 'total_gaji',
                        name: 'total_gaji'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return <
                            button type = "button"
                            class = "btn btn-sm btn-success"
                            onclick = "editData(${row.id})" > Edit < /button> <
                                button type = "button"
                            class = "btn btn-sm btn-danger"
                            onclick = "deleteData(${row.id})" > Delete < /button>;
                        }
                    }
                ],
            });

            window.editData = function(id) {
                $.get(/gaji/$ {
                        id
                    }
                    /edit, function (data) {
                    $('#modal-id').val(data.id); $('#karyawan_id').val(data.karyawan_id).trigger(
                    'change'); $('#jabatan_id').val(data.jabatan_id).trigger('change'); $('#absensi_id')
                    .val(data.absensi_id).trigger('change'); $('#gaji_pokok').val(data.gaji_pokok); $(
                        '#tunjangan_jabatan').val(data.tunjangan_jabatan); $('#total_gaji').val(data
                        .total_gaji);

                    $('#modalForm').attr('action', /gaji/$ {
                        id
                    }).prepend('<input type="hidden" name="_method" value="PATCH">'); $(
                        '#addDataModalLabel').text('Edit Data'); $('#addDataModal').modal('show');
                }).fail(function(xhr, status, error) {
                console.error("Edit Error:", status, error);
                Swal.fire("Error", "Failed to load data!", "error");
            });
        }

        window.confirmSave = function() {
            Swal.fire({
                title: 'Yakin ingin menyimpan?',
                text: 'Pastikan data yang diisi sudah benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modalForm').submit();
                }
            });
        }

        window.deleteData = function(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang sudah dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: /gaji/$ {
                            id
                        },
                        type: "POST",
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil!', response.pesan, 'success').then(() => {
                                table.ajax.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("Delete Error:", status, error);
                            Swal.fire('Gagal!', 'Terjadi kesalahan: ' + error, 'error');
                        }
                    });
                }
            });
        }
        });
    </script>
@endsection
