@extends('admin.components.main')

@section('title', 'Aktivasi Siswa Pending')
@section('page-title', 'Aktivasi Siswa Pending')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Akun Siswa Menunggu Aktivasi</h6>
            <a href="{{ route('admin.site-users.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-list-ul me-1"></i> Lihat Semua Siswa
            </a>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')

            @if ($pendingUsers->isEmpty())
                <div class="alert alert-info text-center">
                    Tidak ada akun siswa yang menunggu aktivasi.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover datatable" id="dataTablePendingUsers" width="100%"
                        cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kelas</th>
                                <th>Tgl Daftar</th>
                                <th class="text-center action-column">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingUsers as $user)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $user->nis }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->class ?: '-' }}</td>
                                    <td>{{ $user->created_at ? $user->created_at->isoFormat('D MMM YYYY, HH:mm') : '-' }}
                                    </td>
                                    <td class="text-center action-column">
                                        <form action="{{ route('admin.site-users.activate', $user) }}" method="POST"
                                            class="d-inline me-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Aktifkan">
                                                <i class="bi bi-check-circle-fill"></i> Aktifkan
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm" title="Tolak & Hapus"
                                            data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $user->id }}">
                                            <i class="bi bi-x-octagon-fill"></i> Tolak
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($pendingUsers as $user)
                    <div class="modal fade" id="rejectModal-{{ $user->id }}" tabindex="-1"
                        aria-labelledby="rejectModalLabel-{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="rejectModalLabel-{{ $user->id }}">Konfirmasi
                                        Penolakan</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menolak dan menghapus registrasi untuk: <br>
                                    <strong>{{ $user->name }} ({{ $user->nis }})</strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.site-users.reject', $user) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Ya, Tolak & Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection

@section('script')
    @include('admin.components.datatable_script', ['table_id' => 'dataTablePendingUsers'])
@endsection
