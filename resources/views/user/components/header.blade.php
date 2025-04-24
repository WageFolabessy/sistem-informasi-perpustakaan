<header class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="30" height="30"
                class="d-inline-block align-text-top me-1">
            {{ config('app.name', 'SIMPerpus') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainUserNavbar"
            aria-controls="mainUserNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainUserNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                @auth('web')
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('catalog.*') ? 'active' : '' }}"
                            href="{{ route('catalog.index') }}">
                            <i class="bi bi-search me-1"></i> Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{-- Request::is('riwayat-pinjam*') ? 'active' : '' --}}" href="#">
                            <i class="bi bi-clock-history me-1"></i> Riwayat Pinjam
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{-- Request::is('booking-saya*') ? 'active' : '' --}}" href="#">
                            <i class="bi bi-journal-bookmark-fill me-1"></i> Booking Saya
                        </a>
                    </li>

                    <li class="nav-item d-none d-lg-block mx-2 border-end" style="height: 20px;"></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="notificationDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                            <i class="bi bi-bell-fill position-relative fs-5">
                                {{-- Badge Notifikasi --}}
                            </i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                            <li><a class="dropdown-item disabled" href="#"><small>Belum ada notifikasi</small></a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-1"></i>
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item {{-- Request::is('profil*') ? 'active' : '' --}}" href="#">
                                    <i class="bi bi-person-lines-fill"></i> Profil Saya</a>
                            </li>
                            <li><a class="dropdown-item {{-- Request::is('denda*') ? 'active' : '' --}}" href="#">
                                    <i class="bi bi-cash-coin"></i> Denda Saya</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="dropdown-item text-danger"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                @guest('web')
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-search me-1"></i> Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn btn-primary btn-sm px-3 me-2 text-white active"
                            href="{{ route('login') }}">Login</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-sm px-3 text-white active"
                                href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>
    </div>
</header>
