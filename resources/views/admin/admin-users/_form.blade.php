@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="nip" class="form-label">NIP</label>
        <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip"
            value="{{ old('nip', $adminUser->nip ?? '') }}" required>
        @error('nip')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $adminUser->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
        value="{{ old('email', $adminUser->email ?? '') }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<hr>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
            name="password" {{ isset($adminUser) ? '' : 'required' }}>
        @if (isset($adminUser))
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
        @endif
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password_confirmation"
            name="password_confirmation">
    </div>
</div>

@if (isset($adminUser))
    <div class="mb-3 form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
            {{ old('is_active', $adminUser->is_active ?? false) ? 'checked' : '' }} {{-- Nonaktifkan jika ini akun admin yang sedang login --}}
            {{ $adminUser->id === Auth::guard('admin')->id() ? 'disabled' : '' }}>
        <label class="form-check-label" for="is_active">Akun Aktif</label>
        @if ($adminUser->id === Auth::guard('admin')->id())
            <small class="form-text text-danger d-block">Anda tidak bisa menonaktifkan akun sendiri.</small>
        @endif
        @error('is_active')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
@endif

<div class="d-flex justify-content-end">
    <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
