@extends('admin.components.main')

@section('title', 'Dashboard Utama')
@section('page-title', 'Dashboard Utama')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Judul Buku</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalBooks ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-journal-richtext fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Total Eksemplar</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalCopies ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-book-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Siswa Aktif</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $activeStudents ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Peminjaman Aktif</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $activeBorrowings ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-up-right-square-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Aktivitas Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover datatable" id="dataTableActivity" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Aktivitas</th>
                            <th>Detail</th>
                            <th>Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>21 Apr 2025 13:30</td>
                            <td>Peminjaman</td>
                            <td>Citra Lestari meminjam Laskar Pelangi (LP002)</td>
                            <td>Admin Budi</td>
                        </tr>
                        <tr>
                            <td>21 Apr 2025 10:15</td>
                            <td>Registrasi Siswa</td>
                            <td>Akun baru: Doni Saputra (12346)</td>
                            <td>Sistem</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('css')
@endsection

@section('script')
@endsection
