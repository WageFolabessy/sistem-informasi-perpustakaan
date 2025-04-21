@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="nis" class="form-label">NIS</label>
        <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis"
            value="{{ old('nis', $siteUser->nis ?? '') }}" required>
        @error('nis')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $siteUser->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
        value="{{ old('email', $siteUser->email ?? '') }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="class" class="form-label">Kelas</label>
        <input type="text" class="form-control @error('class') is-invalid @enderror" id="class" name="class"
            value="{{ old('class', $siteUser->class ?? '') }}">
        @error('class')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="major" class="form-label">Jurusan</label>
        <input type="text" class="form-control @error('major') is-invalid @enderror" id="major" name="major"
            value="{{ old('major', $siteUser->major ?? '') }}">
        @error('major')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3">
    <label for="phone_number" class="form-label">Nomor Telepon</label>
    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
        name="phone_number" value="{{ old('phone_number', $siteUser->phone_number ?? '') }}">
    @error('phone_number')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<hr>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
            name="password" {{ isset($siteUser) ? '' : 'required' }}>
        @if (isset($siteUser))
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

@if (isset($siteUser))
    <div class="mb-3 form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
            {{ old('is_active', $siteUser->is_active ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Akun Aktif</label>
        @error('is_active')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
@endif

<div class="d-flex justify-content-end">
    <a href="{{ route('admin.site-users.index') }}" class="btn btn-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
