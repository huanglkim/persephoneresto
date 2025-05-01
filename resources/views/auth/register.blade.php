@extends('layouts.app')

@section('content')
    <div class="container" style="height: 100vh; display: flex; justify-content: center; align-items: center;">
        <div class="row justify-content-center w-100">
            <div class="col-md-8">
                <div class="card shadow-lg" style="border-radius: 20px;">
                    <div class="card-header d-flex align-items-center" style="background-color: #d66000; color: white; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                        <!-- Logo di kiri -->
                        <img src="{{ url('assets/slider/logo.png') }}" alt="Logo" style="width: 100px; height: auto;">
                        <h4 style="flex-grow: 1; text-align: center; font-size: 30px; margin: 0;">
                            {{ __('Silahkan Daftar terlebih dahulu!') }}
                        </h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row mb-4">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Nama') }}</label>

                               <div class="col-md-8">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                               <div class="col-md-8">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                               <div class="col-md-8">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Ulangi Password') }}</label>

                               <div class="col-md-8">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn" style="background-color: #d66000; color: white;">
                                        {{ __('Daftar') }}
                                    </button>

                                    @if (Route::has('login'))
                                        <a class="btn btn-link" href="{{ route('login') }}">
                                            {{ __('Login') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
