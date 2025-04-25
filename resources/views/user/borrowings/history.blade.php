@extends('user.components.main')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman Buku')

@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-arrow-up-right-square-fill me-2"></i>Buku Sedang Dipinjam
            </h6>
        </div>
        <div class="card-body">
            @if ($activeBorrowings->isEmpty())
                <div class="alert alert-info text-center mb-0">
                    Anda sedang tidak meminjam buku.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover" id="tableActiveBorrowings">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" width="10%">Sampul</th>
                                <th scope="col">Judul Buku</th>
                                <th scope="col">Kode Eksemplar</th>
                                <th scope="col">Tgl Pinjam</th>
                                <th scope="col">Tgl Jatuh Tempo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeBorrowings as $borrowing)
                                <tr class="align-middle">
                                    <td>
                                        <img src="{{ $borrowing->bookCopy?->book?->cover_image ? asset('storage/' . $borrowing->bookCopy->book->cover_image) : asset('assets/images/no-image-book.png') }}"
                                            alt="{{ $borrowing->bookCopy?->book?->title ?? 'Buku' }}" class="img-thumbnail"
                                            style="max-width: 50px; height: auto;">
                                    </td>
                                    <td>
                                        <a href="{{ route('catalog.show', $borrowing->bookCopy?->book?->slug ?? '#') }}"
                                            class="text-decoration-none text-dark fw-medium">
                                            {{ $borrowing->bookCopy?->book?->title ?? 'Judul Tidak Diketahui' }}
                                        </a>
                                    </td>
                                    <td>{{ $borrowing->bookCopy?->copy_code ?? 'N/A' }}</td>
                                    <td>{{ $borrowing->borrow_date?->isoFormat('D MMM YYYY') ?? '-' }}</td>
                                    <td class="{{ $borrowing->is_overdue ? 'text-danger fw-bold' : '' }}">
                                        {{ $borrowing->due_date?->isoFormat('D MMM YYYY') ?? '-' }}
                                        @if ($borrowing->is_overdue)
                                            <span class="badge bg-danger ms-1">Lewat Tempo!</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Terdahulu</h6>
        </div>
        <div class="card-body">
            @if ($pastBorrowings->isEmpty())
                <div class="alert alert-info text-center mb-0">
                    Anda belum memiliki riwayat peminjaman.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover" id="tablePastBorrowings">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Judul Buku</th>
                                <th scope="col">Kode Eksemplar</th>
                                <th scope="col">Tgl Pinjam</th>
                                <th scope="col">Tgl Kembali</th>
                                <th scope="col" class="text-center">Status Akhir</th>
                                <th scope="col" class="text-center">Denda & Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pastBorrowings as $borrowing)
                                <tr class="align-middle">
                                    <td>
                                        <a href="{{ route('catalog.show', $borrowing->bookCopy?->book?->slug ?? '#') }}"
                                            class="text-decoration-none text-dark">
                                            {{ $borrowing->bookCopy?->book?->title ?? 'Judul Tidak Diketahui' }}
                                        </a>
                                    </td>
                                    <td>{{ $borrowing->bookCopy?->copy_code ?? 'N/A' }}</td>
                                    <td>{{ $borrowing->borrow_date?->isoFormat('D MMM YYYY') ?? '-' }}</td>
                                    <td>{{ $borrowing->return_date?->isoFormat('D MMM YYYY') ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($borrowing->status)
                                            <span
                                                class="badge bg-{{ $borrowing->status->badgeColor() }}">{{ $borrowing->status->label() }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($borrowing->fine)
                                            Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}
                                            <span
                                                class="ms-1 badge bg-{{ $borrowing->fine->status->badgeColor() }}">{{ $borrowing->fine->status->label() }}</span>

                                            @if (!empty($borrowing->fine->notes))
                                                <button type="button" class="btn btn-xs btn-outline-secondary ms-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#notesModal-{{ $borrowing->fine->id }}"
                                                    title="Lihat Catatan Denda">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </button>
                                            @endif
                                            @if ($borrowing->fine->status == App\Enum\FineStatus::Unpaid)
                                                <a href="#" class="btn btn-xs btn-warning ms-1"
                                                    title="Lihat Detail Denda"><i class="bi bi-cash-coin"></i></a>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        @if ($pastBorrowings->total() > 0)
                            <small class="text-muted">
                                Menampilkan {{ $pastBorrowings->firstItem() }}
                                hingga {{ $pastBorrowings->lastItem() }}
                                dari {{ $pastBorrowings->total() }} riwayat
                            </small>
                        @endif
                    </div>
                    <div>
                        {{ $pastBorrowings->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @foreach ($pastBorrowings as $borrowing)
        @if ($borrowing->fine && !empty($borrowing->fine->notes))
            <div class="modal fade" id="notesModal-{{ $borrowing->fine->id }}" tabindex="-1"
                aria-labelledby="notesModalLabel-{{ $borrowing->fine->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="notesModalLabel-{{ $borrowing->fine->id }}">
                                <i class="bi bi-chat-left-text me-2"></i>Catatan Denda
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Buku:</strong> {{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}
                                ({{ $borrowing->bookCopy?->copy_code ?? 'N/A' }})
                            </p>
                            <p><strong>Jumlah Denda:</strong> Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}
                            </p>
                            <hr>
                            <p><strong>Catatan:</strong></p>
                            <p style="white-space: pre-wrap;">{!! nl2br(e($borrowing->fine->notes)) !!}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endsection

@section('css')
    <style>
        .btn-xs {
            --bs-btn-padding-y: .1rem;
            --bs-btn-padding-x: .3rem;
            --bs-btn-font-size: .75rem;
        }
    </style>
@endsection

@section('script')
@endsection
