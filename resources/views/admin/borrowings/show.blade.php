@extends('admin.components.main')

@section('title', 'Detail Peminjaman')
@section('page-title')
    Detail Peminjaman: {{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}
@endsection

@section('content')
    <div class="row">
        {{-- Kolom Utama (Info Peminjaman & Buku) --}}
        <div class="col-lg-8">
            {{-- Card Detail Peminjaman --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Peminjaman</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4 col-md-3">Status Peminjaman</dt>
                        <dd class="col-sm-8 col-md-9">
                            @if ($borrowing->status)
                                <span
                                    class="badge bg-{{ $borrowing->status->badgeColor() }} fs-6">{{ $borrowing->status->label() }}</span>
                            @else
                                -
                            @endif
                        </dd>

                        <dt class="col-sm-4 col-md-3">Tanggal Pinjam</dt>
                        <dd class="col-sm-8 col-md-9">
                            {{ $borrowing->borrow_date ? $borrowing->borrow_date->isoFormat('dddd, D MMMM YYYY') : '-' }}
                        </dd>

                        <dt class="col-sm-4 col-md-3">Jatuh Tempo</dt>
                        <dd class="col-sm-8 col-md-9">
                            {{ $borrowing->due_date ? $borrowing->due_date->isoFormat('dddd, D MMMM YYYY') : '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Tanggal Kembali</dt>
                        <dd class="col-sm-8 col-md-9">
                            {{ $borrowing->return_date ? $borrowing->return_date->isoFormat('dddd, D MMMM YYYY') : '-' }}
                        </dd>

                        <dt class="col-sm-4 col-md-3">Admin Peminjam</dt>
                        <dd class="col-sm-8 col-md-9">{{ $borrowing->loanProcessor?->name ?: '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Admin Pengembali</dt>
                        <dd class="col-sm-8 col-md-9">{{ $borrowing->returnProcessor?->name ?: '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Denda</dt>
                        <dd class="col-sm-8 col-md-9">
                            @if ($borrowing->fine)
                                Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}
                                <span class="ms-2 badge bg-{{ $borrowing->fine->status->badgeColor() }}">
                                    {{ $borrowing->fine->status->label() }}
                                </span>
                                @if ($borrowing->fine->status === App\Enum\FineStatus::Paid)
                                    {{-- Sesuaikan Namespace Enum --}}
                                    <small class="d-block text-muted">Lunas pada
                                        {{ $borrowing->fine->payment_date?->isoFormat('D MMM YYYY, HH:mm') }}</small>
                                @endif
                            @else
                                -
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            {{-- Card Detail Buku --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Buku Yang Dipinjam</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4 col-md-3">Judul</dt>
                        <dd class="col-sm-8 col-md-9">{{ $borrowing->bookCopy?->book?->title ?: '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Kode Eksemplar</dt>
                        <dd class="col-sm-8 col-md-9">{{ $borrowing->bookCopy?->copy_code ?: '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">ISBN</dt>
                        <dd class="col-sm-8 col-md-9">{{ $borrowing->bookCopy?->book?->isbn ?: '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Lokasi Rak</dt>
                        <dd class="col-sm-8 col-md-9">{{ $borrowing->bookCopy?->book?->location ?: '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Kondisi Eksemplar</dt>
                        <dd class="col-sm-8 col-md-9">
                            @if ($borrowing->bookCopy?->condition)
                                <span class="badge bg-{{ $borrowing->bookCopy->condition->badgeColor() }}">
                                    {{ $borrowing->bookCopy->condition->label() }}
                                </span>
                            @else
                                -
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan (Info Siswa) --}}
        <div class="col-lg-4">
            {{-- Card Detail Siswa --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Siswa Peminjam</h6>
                </div>
                <div class="card-body">
                    {{-- Gunakan dl class="row" agar konsisten --}}
                    <dl class="row">
                        <dt class="col-sm-4">NIS</dt>
                        <dd class="col-sm-8">{{ $borrowing->siteUser?->nis ?: '-' }}</dd>

                        <dt class="col-sm-4">Nama</dt>
                        <dd class="col-sm-8">{{ $borrowing->siteUser?->name ?: '-' }}</dd>

                        <dt class="col-sm-4">Kelas</dt>
                        <dd class="col-sm-8">{{ $borrowing->siteUser?->class ?: '-' }}</dd>

                        <dt class="col-sm-4">Jurusan</dt>
                        <dd class="col-sm-8">{{ $borrowing->siteUser?->major ?: '-' }}</dd>
                    </dl>
                </div>
                {{-- Tambahkan link ke detail siswa jika ada --}}
                {{-- <div class="card-footer text-end">
                      <a href="{{ route('admin.site-users.edit', $borrowing->siteUser) }}" class="btn btn-sm btn-outline-primary">Lihat Detail Siswa</a>
                 </div> --}}
            </div>
        </div>
    </div>

    {{-- Tombol Aksi di Bawah --}}
    <div class="d-flex justify-content-start mb-4">
        <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Peminjaman
        </a>
        {{-- Tombol Aksi Lain? Misal Cetak Bukti? --}}
    </div>
@endsection

@section('css')
    <style>
        /* Atur margin bawah untuk dt dan dd agar rapi */
        dl.row dt {
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        dl.row dd {
            margin-bottom: 0.75rem;
        }
    </style>
@endsection

@section('script')
@endsection
