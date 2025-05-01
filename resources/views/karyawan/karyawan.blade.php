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

        .btn {
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
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
        }
    </style>

@endsection

@section('content')
    <div class="container">
        <div class="karyawan mt-5">
            <div class="text-right mb-3">
                <button class="btn btn-modern" data-bs-toggle="modal" data-bs-target="#addKaryawanModal">
                    <i class="fas fa-plus"></i> Tambah Karyawan
                </button>
            </div>
            <a href="{{ route('karyawan.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export
            </a>
            </button>
            <hr>
            <table id="karyawanTable" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Email</th>
                        <th>Tanggal Masuk</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawans as $karyawan)
                        <tr data-id="{{ $karyawan->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $karyawan->nama_karyawan }}</td>
                            <td>{{ $karyawan->user ? $karyawan->user->email : 'N/A' }}</td>
                            <td>{{ $karyawan->tanggal_masuk }}</td>
                            <td>{{ $karyawan->divisi->nama }}</td>
                            <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
                            <td>
                                @if ($karyawan->user && $karyawan->user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-modern btn-edit" data-id="{{ $karyawan->id }}"
                                    data-bs-toggle="modal" data-bs-target="#editKaryawanModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-lilac btn-delete" data-id="{{ $karyawan->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr> @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal
        fade" id="addKaryawanModal" tabindex="-1" aria-labelledby="addKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addKaryawanForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter your password">
                        <div class="input-group-append">
                            <span id="togglePassword" class="input-group-text" style="cursor: pointer;">
                                <i class="fas fa-eye-slash" id="togglePasswordIcon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
                    </div>
                    <div class="mb-3">
                        <label for="divisi_id" class="form-label">Divisi</label>
                        <select class="form-select" id="divisi_id" name="divisi_id" required>
                            <option value="">Pilih Divisi</option>
                            @foreach ($divisis as $divisi)
                                <option value="{{ $divisi->id }}">{{ $divisi->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan_id" class="form-label">Jabatan</label>
                        <select class="form-select" id="jabatan_id" name="jabatan_id" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach ($jabatans as $jabatan)
                                <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="karyawan">Karyawan</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="">Pilih Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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

    <div class="modal fade" id="editKaryawanModal" tabindex="-1" aria-labelledby="editKaryawanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKaryawanModalLabel">Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKaryawanForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_nama_karyawan" class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" id="edit_nama_karyawan" name="nama_karyawan"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password (Leave blank to keep current)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="edit_tanggal_masuk" name="tanggal_masuk"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_divisi_id" class="form-label">Divisi</label>
                            <select class="form-select" id="edit_divisi_id" name="divisi_id" required>
                                <option value="">Pilih Divisi</option>
                                @foreach ($divisis as $divisi)
                                    <option value="{{ $divisi->id }}">{{ $divisi->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jabatan_id" class="form-label">Jabatan</label>
                            <select class="form-select" id="edit_jabatan_id" name="jabatan_id" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Role</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="karyawan">Karyawan</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_is_active" class="form-label">Status</label>
                            <select class="form-select" id="edit_is_active" name="is_active" required>
                                <option value="">Pilih Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');

        togglePassword.addEventListener('click', function() {
            // Toggle tipe input
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            // Toggle ikon
            togglePasswordIcon.classList.toggle('fa-eye');
            togglePasswordIcon.classList.toggle('fa-eye-slash');
        });
        // Function to format date as YYYY-MM-DD
        function formatDate(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Handle add karyawan form submit
        $('#addKaryawanForm').on('submit', function(e) {
            e.preventDefault();

            let nama_karyawan = $('#nama_karyawan').val();
            let email = $('#email').val();
            let password = $('#password').val();
            let tanggal_masuk = $('#tanggal_masuk').val(); // Get the date string
            tanggal_masuk = formatDate(tanggal_masuk); // Format the date
            let divisi_id = $('#divisi_id').val();
            let jabatan_id = $('#jabatan_id').val();
            let role = $('#role').val();
            let is_active = $('#is_active').val();


            $.ajax({
                url: '{{ route('karyawan.store') }}',
                method: 'POST',
                data: {
                    nama_karyawan: nama_karyawan,
                    email: email,
                    password: password,
                    tanggal_masuk: tanggal_masuk,
                    divisi_id: divisi_id,
                    jabatan_id: jabatan_id,
                    role: role,
                    is_active: is_active,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        $('#addKaryawanModal').modal('hide');
                        // Reload karyawan table
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan, coba lagi.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    if (xhr.status === 422) {
                        // Handle validation errors
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        for (let key in errors) {
                            errorMessage += errors[key].join('<br>'); // Combine errors for each field
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: 'Please check the following fields:<br>' + errorMessage,
                        });
                    } else if (xhr.status === 500) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'A server error occurred. Please try again later.',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan, coba lagi.',
                        });
                    }
                }
            });
        });

        // Handle edit karyawan
        $('.btn-edit').on('click', function() {
            let karyawanId = $(this).data('id');
            $.get('{{ url('karyawan/edit') }}/' + karyawanId, function(response) {
                if (response) {
                    $('#edit_id').val(response.id);
                    $('#edit_nama_karyawan').val(response.nama_karyawan);
                    $('#edit_email').val(response.email);
                    $('#edit_tanggal_masuk').val(response.tanggal_masuk);
                    $('#edit_divisi_id').val(response.divisi_id);
                    $('#edit_jabatan_id').val(response.jabatan_id);
                    $('#edit_role').val(response.role);
                    $('#edit_is_active').val(response.is_active); // Populate is_active field
                }
            });
        });

        $('#editKaryawanForm').on('submit', function(e) {
            e.preventDefault();

            let karyawanId = $('#edit_id').val();
            let nama_karyawan = $('#edit_nama_karyawan').val();
            let email = $('#edit_email').val();
            let tanggal_masuk = $('#edit_tanggal_masuk').val();
            tanggal_masuk = formatDate(tanggal_masuk);
            let divisi_id = $('#edit_divisi_id').val();
            let jabatan_id = $('#edit_jabatan_id').val();
            let role = $('#edit_role').val();
            let is_active = $('#edit_is_active').val();
            let password = $('#edit_password').val(); // Get password

            $.ajax({
                url: '{{ route('karyawan.store') }}',
                method: 'POST',
                data: {
                    id: karyawanId,
                    nama_karyawan: nama_karyawan,
                    email: email,
                    tanggal_masuk: tanggal_masuk,
                    divisi_id: divisi_id,
                    jabatan_id: jabatan_id,
                    role: role,
                    is_active: is_active,
                    password: password, // Include password
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        $('#editKaryawanModal').modal('hide');
                        // Reload karyawan table
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan, coba lagi.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    if (xhr.status === 422) {
                        // Handle validation errors
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        for (let key in errors) {
                            errorMessage += errors[key].join('<br>'); // Combine errors for each field
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: 'Please check the following fields:<br>' + errorMessage,
                        });
                    } else if (xhr.status === 500) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'A server error occurred. Please try again later.',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan, coba lagi.',
                        });
                    }
                }
            });
        });

        // Handle delete karyawan
        $('.btn-delete').on('click', function() {
            let karyawanId = $(this).data('id');

            if (confirm('Apakah Anda yakin ingin menghapus karyawan ini?')) {
                $.ajax({
                    url: '{{ url('karyawan/destroy') }}/' + karyawanId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove karyawan row
                            $('tr[data-id="' + karyawanId + '"]').remove();
                            Swal.fire(
                                'Deleted!',
                                response.pesan,
                                'success'
                            );
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.pesan,
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan, coba lagi.',
                        });
                    }
                });
            }
        });
    </script>
@endsection
