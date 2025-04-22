@extends('admin.components.main')

@section('title', 'Manajemen Denda')
@section('page-title', 'Manajemen Denda')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Denda</h6>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')

            @if ($fines->isEmpty())
                <div class="alert alert-info text-center">
                    Tidak ada data denda.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped datatable" id="dataTableFines" width="100%"
                        cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center no-sort" width="1%">No</th>
                                <th>Peminjam</th>
                                <th>Judul Buku</th>
                                <th>Jumlah Denda (Rp)</th>
                                <th class="text-center">Status</th>
                                <th>Tgl Bayar/Bebas</th>
                                <th>Admin Proses</th>
                                <th class="text-center action-column no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fines as $fine)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $fine->borrowing?->siteUser?->name ?? 'N/A' }}<br>
                                        <small class="text-muted">NIS:
                                            {{ $fine->borrowing?->siteUser?->nis ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        {{ $fine->borrowing?->bookCopy?->book?->title ?? 'N/A' }}<br>
                                        <small class="text-muted">Kode:
                                            {{ $fine->borrowing?->bookCopy?->copy_code ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-end">{{ number_format($fine->amount, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if ($fine->status)
                                            <span
                                                class="badge bg-{{ $fine->status->badgeColor() }}">{{ $fine->status->label() }}</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $fine->payment_date ? $fine->payment_date->isoFormat('D MMM YYYY, HH:mm') : '-' }}
                                    </td>
                                    <td>{{ $fine->paymentProcessor?->name ?? '-' }}</td>
                                    <td class="action-column text-center">
                                        @if ($fine->status === App\Enum\FineStatus::Unpaid)
                                            <div class="btn-group btn-group-sm">
                                                <form action="{{ route('admin.fines.pay', $fine) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Tandai denda ini sebagai LUNAS?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success" title="Tandai Lunas">
                                                        <i class="bi bi-cash-coin"></i> Bayar
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" title="Bebaskan Denda"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#waiveModal-{{ $fine->id }}">
                                                    <i class="bi bi-slash-circle"></i> Bebaskan
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Modal Bebaskan Denda --}}
                @foreach ($fines as $fine)
                    @if ($fine->status === App\Enum\FineStatus::Unpaid)
                        <div class="modal fade" id="waiveModal-{{ $fine->id }}" tabindex="-1"
                            aria-labelledby="waiveModalLabel-{{ $fine->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="waiveModalLabel-{{ $fine->id }}">Konfirmasi
                                            Bebaskan Denda</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin membebaskan denda sebesar <strong>Rp
                                            {{ number_format($fine->amount, 0, ',', '.') }}</strong> untuk peminjaman buku
                                        <strong>{{ $fine->borrowing?->bookCopy?->book?->title ?? 'N/A' }}</strong> oleh
                                        <strong>{{ $fine->borrowing?->siteUser?->name ?? 'N/A' }}</strong>?
                                        {{-- Bisa ditambahkan input untuk alasan pembebasan jika perlu --}}
                                        {{-- <textarea name="waiver_notes" class="form-control mt-2" placeholder="Alasan pembebasan (opsional)"></textarea> --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.fines.waive', $fine) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning">Ya, Bebaskan Denda</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
    @include('admin.components.datatable_script', ['table_id' => 'dataTableFines'])
@endsection
