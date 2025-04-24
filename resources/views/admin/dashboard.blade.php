@extends('admin.components.main')

@section('title', 'Dashboard Utama')
@section('page-title', 'Dashboard Utama')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <a href="{{ route('admin.books.index') }}" class="text-decoration-none">
                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                    Total Judul Buku</div>
                                <div class="h5 mb-0 fw-bold text-primary">{{ $totalBooks }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-journal-richtext fs-2 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100 py-2">
                <a href="{{ route('admin.books.index') }}" class="text-decoration-none">
                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                    Total Eksemplar</div>
                                <div class="h5 mb-0 fw-bold text-info">{{ $totalCopies }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-book-fill fs-2 text-info"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <a href="{{ route('admin.site-users.index') }}" class="text-decoration-none">
                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                    Siswa Aktif</div>
                                <div class="h5 mb-0 fw-bold text-success">{{ $activeStudents }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people-fill fs-2 text-success"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <a href="{{ route('admin.borrowings.index') }}" class="text-decoration-none">
                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                    Peminjaman Aktif</div>
                                <div class="h5 mb-0 fw-bold text-warning">{{ $activeBorrowings }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-arrow-up-right-square-fill fs-2 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Peminjaman Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover datatable" id="tableRecentActivity" width="100%"
                    cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="20%">Waktu Pinjam</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Kode Eksemplar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center no-sort">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentBorrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->borrow_date?->isoFormat('D MMM YY, HH:mm') ?? '-' }}</td>
                                <td>{{ $borrowing->siteUser?->name ?? 'N/A' }}</td>
                                <td>{{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}</td>
                                <td>{{ $borrowing->bookCopy?->copy_code ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @if ($borrowing->status)
                                        <span
                                            class="badge bg-{{ $borrowing->status->badgeColor() }}">{{ $borrowing->status->label() }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center action-column">
                                    <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="btn btn-sm btn-info"
                                        title="Lihat Detail Peminjaman">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data peminjaman terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <style>
        .action-column {
            white-space: nowrap;
            width: 1%;
            text-align: center;
        }

        .action-column .btn .bi {
            vertical-align: middle;
        }
    </style>
@endsection

@section('script')
    @include('admin.components.datatable_script', ['table_id' => 'tableRecentActivity'])
@endsection
