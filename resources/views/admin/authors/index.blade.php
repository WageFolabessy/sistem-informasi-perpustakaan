@extends('admin.components.main')

@section('title', 'Manajemen Pengarang')
@section('page-title', 'Manajemen Pengarang')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengarang</h6>
            <a href="{{ route('admin.authors.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengarang
            </a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($authors->isEmpty())
                <div class="alert alert-info text-center">
                    Belum ada data pengarang.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover datatable" id="dataTableAuthors" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pengarang</th>
                                <th>Bio</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($authors as $author)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $author->name }}</td>
                                    <td>{{ Str::limit($author->bio, 50, '...') }}</td>
                                    <td class="action-column">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Buku">
                                            <a href="{{ route('admin.authors.show', $author) }}" class="btn btn-info"
                                                title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-warning"
                                                title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Hapus"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $author->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @foreach ($authors as $author)
                    <div class="modal fade" id="deleteModal-{{ $author->id }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel-{{ $author->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="deleteModalLabel-{{ $author->id }}">Konfirmasi Hapus
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus pengarang: <strong>{{ $author->name }}</strong>?
                                    Tindakan ini tidak dapat dibatalkan dan mungkin memengaruhi data buku terkait.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.authors.destroy', $author) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
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
    <script>
        $(document).ready(function() {
            var table = $('#dataTableAuthors');
            if (table.length) {
                table.DataTable({
                    paging: true,
                    searching: true,
                    responsive: true,
                });
            }
        });
    </script>
@endsection
