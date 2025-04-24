<!DOCTYPE html>
{{-- Menggunakan bahasa Indonesia --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{-- Judul halaman dinamis, default 'Selamat Datang' --}}
    <title>{{ config('app.name', 'SIMPerpus') }} - @yield('title', 'Selamat Datang')</title>
    <meta name="description" content="Sistem Informasi Perpustakaan SMK" />
    <meta name="author" content="SMK [Nama Sekolah Anda]" />
    {{-- CSRF Token untuk AJAX nanti --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />

    {{-- Menggunakan aset CSS Vendor dari Admin untuk konsistensi & efisiensi --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="{{ asset('assets/admin/vendor/bootstrap.min.css') }}" rel="stylesheet" />
    {{-- CSS Kustom untuk User --}}
    <link href="{{ asset('assets/user/css/custom-user.css') }}" rel="stylesheet" />

    {{-- Placeholder untuk CSS spesifik per halaman --}}
    @yield('css')

</head>

<body class="d-flex flex-column min-vh-100"> {{-- Atur body agar footer menempel di bawah --}}

    {{-- Sertakan Header User --}}
    @include('user.components.header')

    {{-- Konten Utama --}}
    <main class="container mt-4 mb-5 flex-grow-1"> {{-- mt-4 agar tidak tertutup header, mb-5 agar ada jarak ke footer --}}
        {{-- Judul Halaman (jika perlu) --}}
        @hasSection('page-title')
            <h1 class="h3 mb-4 text-gray-800">@yield('page-title')</h1>
        @endif

        {{-- Area konten dinamis --}}
        @yield('content')
    </main>

    {{-- Sertakan Footer User (Opsional) --}}
    @include('user.components.footer')

    {{-- Menggunakan aset JS Vendor dari Admin --}}
    <script src="{{ asset('assets/admin/vendor/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/bootstrap.bundle.min.js') }}"></script>
    {{-- JS Kustom untuk User --}}
    <script src="{{ asset('assets/user/js/custom-user.js') }}"></script>

    {{-- Placeholder untuk JS spesifik per halaman --}}
    @yield('script')

</body>

</html>
