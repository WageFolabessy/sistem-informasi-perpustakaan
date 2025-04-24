@extends('user.components.main')

@section('title', $book->title)
@section('page-title', 'Detail Buku')

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4 text-center text-md-start">
                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/no-image-book-detail.png') }}"
                        class="img-fluid rounded shadow-sm book-detail-cover" alt="{{ $book->title }}">
                </div>

                <div class="col-md-8">
                    <h1 class="h3 fw-bold text-primary mb-2">{{ $book->title }}</h1>

                    <p class="mb-1">
                        <span class="fw-semibold">Pengarang:</span>
                        {{ $book->author?->name ?? '-' }}
                    </p>
                    <p class="mb-3">
                        <span class="fw-semibold">Penerbit:</span>
                        {{ $book->publisher?->name ?? '-' }} (Tahun: {{ $book->publication_year ?? '-' }})
                    </p>

                    <p class="mb-1">
                        <span class="fw-semibold">Kategori:</span>
                        <span class="badge bg-secondary">{{ $book->category?->name ?? '-' }}</span>
                    </p>
                    <p class="mb-3">
                        <span class="fw-semibold">ISBN:</span> {{ $book->isbn ?? '-' }}
                    </p>

                    <p class="mb-1">
                        <span class="fw-semibold">Lokasi Rak:</span> {{ $book->location ?? '-' }}
                    </p>
                    <p class="mb-3">
                        <span class="fw-semibold">Ketersediaan:</span>
                        @if ($totalCopies > 0)
                            <span class="fw-bold {{ $availableCopies > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $availableCopies }}
                            </span> dari <strong>{{ $totalCopies }}</strong> eksemplar tersedia
                        @else
                            <span class="text-danger fw-bold">Tidak ada eksemplar terdaftar</span>
                        @endif
                    </p>

                    <div class="d-grid d-md-block">
                        @if ($availableCopies > 0)
                            <button type="button" class="btn btn-primary btn-lg" disabled
                                title="Fitur Booking Belum Tersedia">
                                <i class="bi bi-journal-bookmark-fill me-1"></i> Booking Buku Ini
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary btn-lg" disabled>
                                Stok Tidak Tersedia
                            </button>
                        @endif
                    </div>
                    <hr class="my-4">

                    <h5 class="fw-semibold">Sinopsis</h5>
                    <div class="text-muted synopsis-text">
                        {!! nl2br(e($book->synopsis ?: 'Sinopsis tidak tersedia.')) !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Katalog
        </a>
    </div>
@endsection

@section('css')
    <style>
        .book-detail-cover {
            max-height: 450px;
            width: auto;
            max-width: 100%;
        }

        .synopsis-text {
            line-height: 1.7;
        }
    </style>
@endsection

@section('script')
@endsection
