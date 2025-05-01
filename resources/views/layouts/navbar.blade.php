<div>
    <nav class="navbar navbar-expand-md navbar-light bg-earthy shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ url('assets/slider/logo.png') }}" alt="Logo" height="30" class="me-2">
                <span class="fw-bold">Persephone<strong>Resto</strong></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            List Product
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            {{-- Tampilkan Tambah Menu hanya untuk Ale Huang --}}
                            @if (Auth::check() && Auth::user()->name === 'Ale Huang')
                                <li><a class="dropdown-item" href="{{ route('produks.tambah') }}">Tambah Menu</a></li>
                            @endif

                            {{-- Semua user bisa lihat daftar menu --}}
                            @foreach ($menus as $menu)
                                <li><a class="dropdown-item"
                                        href="{{ route('produks.menu', $menu->id) }}">{{ $menu->nama }}</a></li>
                            @endforeach

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('produks') }}">Semua Menu Produk</a></li>

                        </ul>
                    </li>
                    {{-- Hanya "Ale Huang" yang bisa lihat bagian Karyawan & Pencapaian --}}
                    @if (Auth::check() && Auth::user()->name === 'Ale Huang')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Karyawan
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('karyawan') }}">Karyawan</a></li>
                                <li><a class="dropdown-item" href="{{ route('jabatan') }}">Jabatan</a></li>
                                <li><a class="dropdown-item" href="{{ route('divisi') }}">Divisi</a></li>
                                <li><a class="dropdown-item" href="{{ route('absensi') }}">Absensi</a></li>
                                <li><a class="dropdown-item" href="{{ route('gaji') }}">Penggajian</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('actual') }}">Pencapaian</a>
                        </li>
                    @endif
                    @if (Auth::check() && Auth::user()->name === 'Ale Huang')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('favitem') }}">
                            Produk Paling Diminati</i>
                        </a>
                    </li>
                    @endif
                </ul>

                <!-- Right Side -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('keranjang') }}">
                            Pesanan <i class="fas fa-shopping-bag ms-1"></i>
                            @if (Auth::check() && $jumlah_pesanan !== 0)
                                <span class="badge bg-danger ms-1">{{ $jumlah_pesanan }}</span>
                            @endif
                        </a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        {{-- @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @endif --}}
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</div>
