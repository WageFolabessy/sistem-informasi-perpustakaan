@extends('admin.components.main')

@section('title', 'Manajemen Buku')
@section('page-title', 'Manajemen Buku')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Buku</h6>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Buku
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

            @if ($books->isEmpty())
                <div class="alert alert-info text-center">
                    Belum ada data buku.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped datatable" id="dataTableBooks"
                        width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Sampul</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th>ISBN</th>
                                <th>Lokasi</th>
                                <th class="text-center">Jml Eksemplar</th>
                                <th class="text-center action-column">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($books as $book)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/no-image.png') }}"
                                            alt="{{ $book->title }}" height="60"
                                            style="max-width: 50px; object-fit: contain;">
                                    </td>
                                    <td>
                                        <span class="truncate-text" title="{{ $book->title }}">
                                            {{ $book->title }}
                                        </span>
                                    </td>
                                    <td>{{ $book->author?->name ?: '-' }}</td>
                                    <td>{{ $book->category?->name ?: '-' }}</td>
                                    <td>{{ $book->isbn ?: '-' }}</td>
                                    <td>{{ $book->location ?: '-' }}</td>
                                    <td class="text-center">{{ $book->copies_count }}</td>
                                    <td class="action-column">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Buku">
                                            <a href="{{ route('admin.books.show', $book) }}" class="btn btn-info"
                                                title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning"
                                                title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Hapus"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $book->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($books as $book)
                    <div class="modal fade" id="deleteModal-{{ $book->id }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel-{{ $book->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="deleteModalLabel-{{ $book->id }}">Konfirmasi Hapus
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus buku: <strong>{{ $book->title }}</strong>? Menghapus
                                    judul buku akan dicegah jika masih ada eksemplar terdaftar.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
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
        .truncate-text {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            vertical-align: middle;
        }

        .action-column {
            white-space: nowrap;
            width: 1%;
            text-align: center;
        }

        .action-column .btn .bi {
            vertical-align: middle;
        }

        td img {
            display: block;
            margin: auto;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#dataTableBooks');
            if (table.length) {
                table.DataTable({
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    info: true,
                    responsive: true,
                    language: {
                        emptyTable: "Belum ada data buku."
                    },
                    columnDefs: [{
                        targets: [1, 8],
                        orderable: false,
                        searchable: false
                    }, {
                        targets: [7],
                        className: 'text-center'
                    }, {
                        targets: [0],
                        className: 'text-center',
                        width: '1%'
                    }]
                });
            }
        });
    </script>
@endsection
