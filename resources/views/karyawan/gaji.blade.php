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
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            background-color: #fffdf9;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .table th,
        .table td {
            padding: 16px;
            text-align: center;
            vertical-align: middle;
        }

        .table thead {
            background-color: #ffd6a5;
            color: #4a3b2c;
        }

        .table tbody tr:hover {
            background-color: #fff1e6;
            cursor: pointer;
        }

        .alert {
            margin-bottom: 20px;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="gaji mt-5">
            <div class="text-right mb-3">
                <button class="btn btn-modern" data-bs-toggle="modal" data-bs-target="#addGajiModal">
                    <i class="fas fa-plus"></i> Tambah Gaji
                </button>
            </div>
            <a href="{{ route('gaji.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export
            </a>
            <hr>
            <table id="gajiTable" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Jumlah Hari Kerja</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan Jabatan</th>
                        <th>Potongan Gaji</th>
                        <th>Total Gaji</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gajis as $gaji)
                        <tr data-id="{{ $gaji->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $gaji->karyawan->nama_karyawan }}</td>
                            <td>{{ $gaji->jabatan->nama_jabatan }}</td>
                            <td>{{ $gaji->absensi ? $gaji->absensi->jumlah_hari_kerja : '-' }}</td>
                            <td>{{ 'Rp. ' . number_format($gaji->jabatan->gaji_pokok, 0, ',', '.') }}</td>
                            <td>{{ 'Rp. ' . number_format($gaji->jabatan->tunjangan_jabatan, 0, ',', '.') }}</td>
                            <td>{{ $gaji->absensi ? 'Rp. ' . number_format($gaji->absensi->potongan_gaji_pokok, 0, ',', '.') : 'Rp. 0' }}</td>
                            <td>{{ 'Rp. ' . number_format($gaji->total_gaji, 0, ',', '.') }}</td>
                            <td>{{ $gaji->keterangan }}</td>
                            <td>
                                <button class="btn btn-modern btn-edit" data-id="{{ $gaji->id }}"
                                    data-bs-toggle="modal" data-bs-target="#editGajiModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-secondary btn-delete" data-id="{{ $gaji->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-lilac btn-print-invoice" data-id="{{ $gaji->id }}">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr> @endforeach
                </tbody>
            </table>

            <div class="footer">
    <button id="printAll" class="btn btn-modern">Print Semua Laporan</button>
    </div>
    </div>
    </div>

    {{-- Modal for Adding and Editing Data --}}
    <div class="modal fade" id="addGajiModal" tabindex="-1" aria-labelledby="addGajiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGajiModalLabel">Tambah Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addGajiForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="karyawan_id" class="form-label">Nama Karyawan</label>
                            <select class="form-select" id="karyawan_id" name="karyawan_id" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jabatan_id">Jabatan</label>
                            <select class="form-control" id="jabatan_id" name="jabatan_id" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}" data-gaji-pokok="{{ $jabatan->gaji_pokok }}"
                                        data-tunjangan="{{ $jabatan->tunjangan_jabatan }}">
                                        {{ $jabatan->nama_jabatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="absensi_id">Absensi</label>
                            <select class="form-control" id="absensi_id" name="absensi_id">
                                <option value="">Pilih Absensi (Opsional)</option>
                                @foreach ($absensis as $absensi)
                                    <option value="{{ $absensi->id }}"
                                        data-potongan="{{ $absensi->potongan_gaji_pokok }}">
                                        {{ $absensi->jumlah_hari_kerja }} Hari Kerja - Potongan:
                                        Rp. {{ number_format($absensi->potongan_gaji_pokok, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gaji_pokok">Gaji Pokok</label>
                            <input type="text" class="form-control" id="gaji_pokok_display" readonly>
                            <input type="hidden" id="gaji_pokok" name="gaji_pokok">
                        </div>
                        <div class="form-group">
                            <label for="tunjangan_jabatan">Tunjangan</label>
                            <input type="text" class="form-control" id="tunjangan_jabatan_display" readonly>
                            <input type="hidden" id="tunjangan_jabatan" name="tunjangan_jabatan">
                        </div>
                        <div class="form-group">
                            <label for="potongan_gaji_pokok">Potongan Gaji</label>
                            <input type="text" class="form-control" id="potongan_gaji_pokok_display" readonly>
                            <input type="hidden" id="potongan_gaji_pokok" name="potongan_gaji_pokok">
                        </div>
                        <div class="form-group">
                            <label for="total_gaji">Total Gaji</label>
                            <input type="text" class="form-control" id="total_gaji" name="total_gaji" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-modern">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editGajiModal" tabindex="-1" aria-labelledby="editGajiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGajiModalLabel">Edit Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editGajiForm">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_karyawan_id" class="form-label">Nama Karyawan</label>
                            <select class="form-select" id="edit_karyawan_id" name="karyawan_id" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_jabatan_id">Jabatan</label>
                            <select class="form-control" id="edit_jabatan_id" name="jabatan_id" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}" data-gaji-pokok="{{ $jabatan->gaji_pokok }}"
                                        data-tunjangan="{{ $jabatan->tunjangan_jabatan }}">
                                        {{ $jabatan->nama_jabatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_absensi_id">Absensi</label>
                            <select class="form-control" id="edit_absensi_id" name="absensi_id">
                                <option value="">Pilih Absensi (Opsional)</option>
                                @foreach ($absensis as $absensi)
                                    <option value="{{ $absensi->id }}"
                                        data-potongan="{{ $absensi->potongan_gaji_pokok }}">
                                        {{ $absensi->jumlah_hari_kerja }} Hari Kerja - Potongan:
                                        Rp. {{ number_format($absensi->potongan_gaji_pokok, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_gaji_pokok">Gaji Pokok</label>
                            <input type="text" class="form-control" id="edit_gaji_pokok_display" readonly>
                            <input type="hidden" id="edit_gaji_pokok" name="gaji_pokok">
                        </div>
                        <div class="form-group">
                            <label for="edit_tunjangan_jabatan">Tunjangan</label>
                            <input type="text" class="form-control" id="edit_tunjangan_jabatan_display" readonly>
                            <input type="hidden" id="edit_tunjangan_jabatan" name="tunjangan_jabatan">
                        </div>
                        <div class="form-group">
                            <label for="edit_potongan_gaji">Potongan Gaji</label>
                            <input type="text" class="form-control" id="edit_potongan_gaji_display" readonly>
                            <input type="hidden" id="edit_potongan_gaji" name="potongan_gaji">
                        </div>
                        <div class="form-group">
                            <label for="edit_total_gaji">Total Gaji</label>
                            <input type="text" class="form-control" id="edit_total_gaji" name="total_gaji" readonly
                                required>
                        </div>
                        <div class="form-group">
                            <label for="edit_keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="edit_keterangan" name="keterangan">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.js"></script>
    <script>
        $(document).ready(function() {
            function calculateTotalGaji() {
                var gajiPokok = parseFloat($('#gaji_pokok').val()) || 0;
                var tunjanganJabatan = parseFloat($('#tunjangan_jabatan').val()) || 0;
                var potonganGaji = parseFloat($('#potongan_gaji').val()) || 0;
                var totalGaji = gajiPokok + tunjanganJabatan - potonganGaji;
                $('#total_gaji').val('Rp. ' + totalGaji.toLocaleString('id-ID'));
            }

            // Function to format the number
            function formatNumber(num) {
                return num.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                });
            }

            // Update gaji pokok and tunjangan when jabatan is selected (for Add Modal)
            $('#jabatan_id').change(function() {
                var selectedOption = $(this).find(':selected');
                var gajiPokok = parseFloat(selectedOption.data('gaji-pokok')) || 0;
                var tunjangan = parseFloat(selectedOption.data('tunjangan')) || 0;
                $('#gaji_pokok_display').val(formatNumber(gajiPokok));
                $('#gaji_pokok').val(gajiPokok);
                $('#tunjangan_jabatan_display').val(formatNumber(tunjangan));
                $('#tunjangan_jabatan').val(tunjangan);
                calculateTotalGaji();
            });

            // Update potongan when absensi is selected (for Add Modal)
            $('#absensi_id').change(function() {
                var selectedOption = $(this).find(':selected');
                var potongan = parseFloat(selectedOption.data('potongan')) || 0;
                $('#potongan_gaji_display').val(formatNumber(potongan));
                $('#potongan_gaji').val(potongan);
                calculateTotalGaji();
            });

            // Update gaji pokok and tunjangan when jabatan is selected (for Edit Modal)
            $('#edit_jabatan_id').change(function() {
                var selectedOption = $(this).find(':selected');
                var gajiPokok = parseFloat(selectedOption.data('gaji-pokok')) || 0;
                var tunjangan = parseFloat(selectedOption.data('tunjangan')) || 0;
                $('#edit_gaji_pokok_display').val(formatNumber(gajiPokok));
                $('#edit_gaji_pokok').val(gajiPokok);
                $('#edit_tunjangan_jabatan_display').val(formatNumber(tunjangan));
                $('#edit_tunjangan_jabatan').val(tunjangan);
                calculateTotalGaji();
            });

            // Update potongan when absensi is selected (for Edit Modal)
            $('#edit_absensi_id').change(function() {
                var selectedOption = $(this).find(':selected');
                var potongan = parseFloat(selectedOption.data('potongan')) || 0;
                $('#edit_potongan_gaji_display').val(formatNumber(potongan));
                $('#edit_potongan_gaji').val(potongan);
                calculateTotalGaji();
            });

            // Initial calculation for Add Modal
            calculateTotalGaji();

            // Handle the adding of new Gaji data
            $('#addGajiForm').submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('gaji.store') }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Include CSRF token
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Data has been added.',
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });

                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to add data.',
                            icon: 'error'
                        });
                    }
                });
            });

            // Handle the editing of Gaji data
            $('#editGajiForm').submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('gaji.update', ':id') }}'.replace(':id', $('#edit_id').val()),
                    method: 'POST', // Keep it as POST
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Data has been updated.',
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update data.',
                            icon: 'error'
                        });
                    }
                });
            });

            // Handle deleting Gaji data
            $('.btn-delete').click(function() {
                let gajiId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/gaji/destroy/' + gajiId, // Corrected route
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content') // Include CSRF token
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Data has been deleted.',
                                    icon: 'success'
                                }).then(() => {
                                    location
                                .reload(); // Reload the page to reflect changes
                                });

                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to delete data.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Print functionality for the invoice
            $('.btn-print-invoice').click(function() {
                let gajiId = $(this).data('id');
                window.open('{{ route('gaji.invoice', ['id' => ':id']) }}'.replace(':id', gajiId),
                    '_blank');
            });

            // Print all data
            $('#printAll').click(function() {
                window.open('{{ route('gaji.printall') }}', '_blank');
            });

            $('#editGajiModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id'); // Extract info from data-* attributes
                var modal = $(this);

                $.ajax({
                    url: '/gaji/edit/' + id, // Corrected route to fetch data for editing
                    type: 'GET',
                    success: function(data) {
                        console.log(data); // Add this line to inspect the response
                        modal.find('#edit_id').val(data.id);
                        modal.find('#edit_karyawan_id').val(data.karyawan_id);

                        // Populate jabatan dropdown and set selected value
                        var jabatanSelect = modal.find('#edit_jabatan_id');
                        jabatanSelect.empty(); // Clear existing options
                        @foreach ($jabatans as $jabatan)
                            jabatanSelect.append($('<option>', {
                                value: '{{ $jabatan->id }}',
                                text: '{{ $jabatan->nama_jabatan }}',
                                'data-gaji-pokok': '{{ $jabatan->gaji_pokok }}',
                                'data-tunjangan': '{{ $jabatan->tunjangan_jabatan }}'
                            }));
                        @endforeach
                        jabatanSelect.val(data.jabatan_id);

                        // Populate absensi dropdown
                        var absensiSelect = modal.find('#edit_absensi_id');
                        absensiSelect.empty();
                        absensiSelect.append($('<option>', {
                            value: null,
                            text: 'Pilih Absensi (Opsional)'
                        }));
                        @foreach ($absensis as $absensi)
                            absensiSelect.append($('<option>', {
                                value: '{{ $absensi->id }}',
                                text: '{{ $absensi->jumlah_hari_kerja }} Hari Kerja - Potongan: Rp. {{ number_format($absensi->potongan_gaji_pokok, 0, ',', '.') }}',
                                'data-potongan': '{{ $absensi->potongan_gaji_pokok }}'
                            }));
                        @endforeach
                        absensiSelect.val(data.absensi_id);

                        // Check if jabatan exists before accessing its properties
                        if (data.jabatan) {
                            modal.find('#edit_gaji_pokok').val(data.jabatan.gaji_pokok);
                            modal.find('#edit_gaji_pokok_display').val(formatNumber(data.jabatan
                                .gaji_pokok));
                            modal.find('#edit_tunjangan_jabatan').val(data.jabatan
                                .tunjangan_jabatan);
                            modal.find('#edit_tunjangan_jabatan_display').val(formatNumber(data
                                .jabatan
                                .tunjangan_jabatan));
                        } else {
                            // Handle the case where data.jabatan is undefined
                            modal.find('#edit_gaji_pokok').val(0);
                            modal.find('#edit_gaji_pokok_display').val(formatNumber(0));
                            modal.find('#edit_tunjangan_jabatan').val(0);
                            modal.find('#edit_tunjangan_jabatan_display').val(formatNumber(0));
                        }

                        modal.find('#edit_potongan_gaji').val(data.absensi ? data.absensi
                            .potongan_gaji_pokok : 0);
                        modal.find('#edit_potongan_gaji_display').val(data.absensi ?
                            formatNumber(data.absensi
                                .potongan_gaji_pokok) : formatNumber(0));
                        modal.find('#edit_total_gaji').val(formatNumber(data.total_gaji));

                        // Trigger change event to update total gaji
                        calculateTotalGaji();
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to fetch data for editing.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@endsection
