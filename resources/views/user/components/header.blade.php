{{-- Navbar Bootstrap 5 - Light background, Shadow, Sticky Top --}}
<header class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm">
    <div class="container">
        {{-- Branding/Logo --}}
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="30" height="30"
                class="d-inline-block align-text-top me-1">
            {{ config('app.name', 'SIMPerpus') }}
        </a>

        {{-- Tombol Toggler untuk Mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainUserNavbar"
            aria-controls="mainUserNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu Navigasi --}}
        <div class="collapse navbar-collapse" id="mainUserNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                {{-- Menu untuk Pengguna yang Sudah Login --}}
                @auth('web')
                    {{-- Guard 'web' adalah default untuk user/siswa --}}
                    <li class="nav-item">
                        {{-- TODO: Ganti href saat route katalog sudah dibuat --}}
                        <a class="nav-link {{-- Route::is('catalog.*') ? 'active' : '' --}}" href="#">
                            <i class="bi bi-search me-1"></i> Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        {{-- TODO: Ganti href saat route riwayat peminjaman sudah dibuat --}}
                        <a class="nav-link {{-- Route::is('user.history.*') ? 'active' : '' --}}" href="#">
                            <i class="bi bi-clock-history me-1"></i> Riwayat Pinjam
                        </a>
                    </li>
                    <li class="nav-item">
                        {{-- TODO: Ganti href saat route booking siswa sudah dibuat --}}
                        <a class="nav-link {{-- Route::is('user.bookings.*') ? 'active' : '' --}}" href="#">
                            <i class="bi bi-journal-bookmark-fill me-1"></i> Booking Saya
                        </a>
                    </li>

                    {{-- Pemisah untuk ikon di layar besar --}}
                    <li class="nav-item d-none d-lg-block mx-2 border-end"></li>

                    {{-- Dropdown Notifikasi (Placeholder) --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="notificationDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                            <i class="bi bi-bell-fill position-relative fs-5">
                                {{-- Badge Notifikasi (Contoh, perlu data backend) --}}
                                {{-- <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="font-size: 0.6em;">
                                     <span class="visually-hidden">Notifikasi baru</span>
                                 </span> --}}
                            </i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                            <li><a class="dropdown-item disabled" href="#">Belum ada notifikasi</a></li>
                        </ul>
                    </li>

                    {{-- Dropdown Menu User --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-1"></i>
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span> {{-- Tampilkan nama hanya di layar besar --}}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            {{-- TODO: Ganti href saat route profil siswa sudah dibuat --}}
                            <li><a class="dropdown-item" href="#">
                                    <i class="bi bi-person-lines-fill me-2"></i> Profil Saya</a>
                            </li>
                            {{-- TODO: Ganti href saat route denda siswa sudah dibuat --}}
                            <li><a class="dropdown-item" href="#">
                                    <i class="bi bi-cash-coin me-2"></i> Denda Saya</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                {{-- Form Logout (route 'logout' bawaan Laravel UI/Breeze) --}}
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="dropdown-item text-danger"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                {{-- Menu untuk Pengguna Tamu (Belum Login) --}}
                @guest('web')
                    <li class="nav-item">
                        {{-- TODO: Ganti href jika route katalog tamu berbeda --}}
                        <a class="nav-link {{-- Route::is('catalog.guest') ? 'active' : '' --}}" href="#">
                            <i class="bi bi-search me-1"></i> Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary btn-sm px-3 me-2 {{ Route::is('login') ? 'active' : '' }}"
                            href="{{ route('login') }}">Login</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-sm px-3 text-white {{ Route::is('register') ? 'active' : '' }}"
                                href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>
    </div>
</header>
