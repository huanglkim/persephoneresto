@extends('layouts.app')
@section('title', 'Coffeshop')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.css" rel="stylesheet">
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('ac.u', $actual->id) }}" method="POST" id="form-submit">
                            @csrf
                            @method('patch')

                            <div class="row">
                                <!-- Bulan Input -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label @error('bulan') class="text-danger" @enderror>
                                            Bulan
                                            @error('bulan') | {{ $message }} @enderror
                                        </label>
                                        <input 
                                            type="text" 
                                            name="bulan" 
                                            value="{{ $actual->bulan }}" 
                                            class="form-control @error('bulan') is-invalid @enderror"
                                        >
                                    </div>
                                </div>

                                <!-- User Input -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label @error('user') class="text-danger" @enderror>
                                            User
                                            @error('user') | {{ $message }} @enderror
                                        </label>
                                        <input 
                                            type="text" 
                                            name="user" 
                                            value="{{ $actual->user }}" 
                                            class="form-control @error('user') is-invalid @enderror"
                                        >
                                    </div>
                                </div>

                                <!-- Hasil Input -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label @error('hasil') class="text-danger" @enderror>
                                            Hasil
                                            @error('hasil') | {{ $message }} @enderror
                                        </label>
                                        <input 
                                            type="text" 
                                            name="hasil" 
                                            value="{{ $actual->hasil }}" 
                                            class="form-control @error('hasil') is-invalid @enderror"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Form Buttons -->
                            <div class="card-footer text-right">
                                <button class="btn btn-modern mr-1" type="submit">Save</button>
                                <button class="btn btn-secondary" type="button" onclick="cancelForm()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-scripts')
    <script>
        // SweetAlert confirmation on form submission
        document.getElementById('form-submit').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission
            const form = this;

            Swal.fire({
                title: 'Apakah kamu yakin ingin menyimpan perubahan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if confirmed
                }
            });
        });

        // SweetAlert confirmation on cancellation
        function cancelForm() {
            Swal.fire({
                title: 'Apakah kamu yakin ingin membatalkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, batal',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.history.back(); // Navigate back to the previous page if confirmed
                }
            });
        }
    </script>
@endpush
