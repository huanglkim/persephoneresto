@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-light">{{ __('Profil Pengguna') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card shadow-sm text-center">
                                <div class="card-body">
                                    @if ($user->gambar)
                                        <img src="{{ asset('storage/' . $user->gambar) }}" alt="{{ $user->name }}" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <h5 class="mt-3">{{ $user->name }}</h5>
                                    <p class="text-muted">{{ $user->jabatan->nama_jabatan }}</p>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">{{ __('Informasi Pribadi') }}</div>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-md-4">{{ __('Nama') }}</dt>
                                        <dd class="col-md-8">{{ $user->name }}</dd>

                                        <dt class="col-md-4">{{ __('Email') }}</dt>
                                        <dd class="col-md-8">{{ $user->email }}</dd>

                                        @if ($user->nohp)
                                            <dt class="col-md-4">{{ __('Nomor Hp') }}</dt>
                                            <dd class="col-md-8">{{ $user->nohp }}</dd>
                                        @endif

                                        <dt class="col-md-4">{{ __('Alamat') }}</dt>
                                        <dd class="col-md-8">{{ $user->alamat ?? '-' }}</dd>

                                        <dt class="col-md-4">{{ __('Jabatan') }}</dt>
                                        <dd class="col-md-8">{{ $user->jabatan->nama_jabatan ?? '-' }}</dd>

                                        {{-- Tambahkan informasi lain sesuai kebutuhan --}}
                                    </dl>
                                    <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-primary">{{ __('Edit Profil') }}</a>
                                </div>
                            </div>

                            {{-- Bagian untuk Project Status atau informasi lain bisa ditambahkan di sini --}}
                            {{-- Contoh: --}}
                            {{-- <div class="card shadow-sm mt-4">
                                <div class="card-header bg-light">{{ __('Project Status') }}</div>
                                <div class="card-body">
                                    -- Isi dengan informasi project atau lainnya --
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection