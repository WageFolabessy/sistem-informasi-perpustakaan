@extends('admin.components.main')

@section('title', 'Catat Peminjaman Baru')
@section('page-title', 'Catat Peminjaman Baru')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Peminjaman Buku</h6>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')
            @include('admin.components.validation_errors')

            <form action="{{ route('admin.borrowings.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Pilih Siswa --}}
                    <div class="col-md-6 mb-3">
                        <label for="site_user_id" class="form-label">Siswa Peminjam</label>
                        <select class="form-select @error('site_user_id') is-invalid @enderror" id="site_user_id"
                            name="site_user_id" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ old('site_user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} (NIS: {{ $student->nis }})
                                </option>
                            @endforeach
                        </select>
                        @error('site_user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- TODO: Ganti dengan select2/tom-select untuk pencarian mudah --}}
                    </div>

                    {{-- Pilih Eksemplar Buku --}}
                    <div class="col-md-6 mb-3">
                        <label for="book_copy_id" class="form-label">Eksemplar Buku</label>
                        <select class="form-select @error('book_copy_id') is-invalid @enderror" id="book_copy_id"
                            name="book_copy_id" required>
                            <option value="">-- Pilih Kode Eksemplar --</option>
                            @foreach ($availableCopies as $copy)
                                <option value="{{ $copy->id }}"
                                    {{ old('book_copy_id') == $copy->id ? 'selected' : '' }}>
                                    {{ $copy->copy_code }} - {{ $copy->book?->title ?? 'Judul Tidak Diketahui' }}
                                </option>
                            @endforeach
                        </select>
                        @error('book_copy_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- TODO: Ganti dengan select2/tom-select --}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="borrow_date" class="form-label">Tanggal Pinjam (Opsional)</label>
                        <input type="date" class="form-control @error('borrow_date') is-invalid @enderror"
                            id="borrow_date" name="borrow_date" value="{{ old('borrow_date', now()->format('Y-m-d')) }}">
                        <small class="form-text text-muted">Kosongkan untuk menggunakan tanggal hari ini.</small>
                        @error('borrow_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Perkiraan Tgl Kembali</label>
                        <input type="text" class="form-control" id="due_date_display" value="Otomatis" readonly disabled>
                        {{-- Bisa diisi otomatis via JS atau biarkan kosong --}}
                    </div>
                </div>


                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save-fill me-1"></i> Catat Peminjaman
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('css')
    {{-- Jika pakai select2/tom-select, tambahkan CSS-nya di sini --}}
@endsection

@section('script')
    {{-- Jika pakai select2/tom-select, tambahkan JS-nya di sini --}}
    {{-- Contoh JS sederhana untuk update perkiraan tgl kembali (perlu Carbon di JS atau kirim setting durasi) --}}
    <script>
        // document.getElementById('borrow_date').addEventListener('change', function() {
        //     const borrowDate = this.value;
        //     const loanDuration = 7; // Ambil dari setting via Blade jika memungkinkan
        //     if(borrowDate) {
        //         let date = new Date(borrowDate);
        //         date.setDate(date.getDate() + loanDuration);
        //         document.getElementById('due_date_display').value = date.toISOString().slice(0,10);
        //     } else {
        //          document.getElementById('due_date_display').value = 'Otomatis';
        //     }
        // });
    </script>
@endsection
