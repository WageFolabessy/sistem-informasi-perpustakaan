@extends('user.components.main')

{{-- Set Judul Halaman --}}
@section('title', 'Dashboard')

{{-- Set Judul di Konten (Opsional) --}}
@section('page-title', 'Selamat Datang!')

{{-- Isi Konten Utama --}}
@section('content')
    <div class="alert alert-success" role="alert">
        {{-- <h4 class="alert-heading">Halo, {{ Auth::user()->name }}!</h4> --}}
        <p>Ini adalah halaman dashboard Anda. Fitur lainnya akan segera ditambahkan.</p>
        <hr>
        <p class="mb-0">Anda bisa mulai menjelajahi katalog buku atau melihat riwayat peminjaman Anda melalui menu di atas.
        </p>
    </div>

    {{-- Nanti bisa ditambahkan widget atau rangkuman lainnya di sini --}}
@endsection

{{-- Kosongkan jika tidak perlu CSS/JS khusus halaman ini --}}
@section('css')
@endsection

@section('script')
@endsection
