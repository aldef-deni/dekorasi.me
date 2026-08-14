@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Ubah nama, email, foto profil, dan kata sandi akun Anda')

@section('content')
  <div class="row g-4">

    {{-- ==================== Kartu profil ==================== --}}
    <div class="col-lg-4">
      <div class="card profile-card">
        <div class="profile-cover"></div>

        <div class="card-body text-center pt-0">
          <div class="profile-avatar-wrap">
            <img id="avatar-preview" src="{{ avatar_url($user) }}"
                 alt="{{ $user->name }}" class="profile-avatar" />
          </div>

          <h5 class="profile-name">{{ $user->name }}</h5>
          <p class="text-body-secondary mb-3">{{ $user->email }}</p>

          <span class="profile-role mb-4">
            <i class="icon-base ti tabler-shield-check icon-16px"></i> Administrator
          </span>

          <hr class="my-4" />

          <form method="POST" action="{{ route('admin.profile.update') }}"
                enctype="multipart/form-data" class="profile-upload text-start">
            @csrf @method('PUT')

            {{-- Nama & email ikut dikirim agar validasi tidak menolak unggahan foto --}}
            <input type="hidden" name="name" value="{{ $user->name }}" />
            <input type="hidden" name="email" value="{{ $user->email }}" />

            <label class="form-label d-flex align-items-center gap-2" for="avatar">
              <i class="icon-base ti tabler-camera text-primary"></i>
              <span>Ganti Foto Profil</span>
            </label>

            <input type="file" class="form-control mb-2 @error('avatar') is-invalid @enderror"
                   id="avatar" name="avatar" accept="image/*" data-preview="#avatar-preview" required />
            @error('avatar') <div class="invalid-feedback d-block mb-2">{{ $message }}</div> @enderror

            <div class="form-text mb-3">JPG, PNG, atau WEBP. Maksimal 2 MB. Sebaiknya berbentuk persegi.</div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="icon-base ti tabler-upload me-1"></i> Unggah Foto
            </button>
          </form>

          @if ($user->avatar)
            <form method="POST" action="{{ route('admin.profile.avatar.destroy') }}" class="mt-3">
              @csrf @method('DELETE')
              <button type="button" class="btn btn-label-danger w-100"
                      data-confirm-delete="Foto profil akan dihapus dan diganti inisial nama.">
                <i class="icon-base ti tabler-trash me-1"></i> Hapus Foto
              </button>
            </form>
          @else
            <p class="text-body-secondary small mt-3 mb-0">
              Belum ada foto — saat ini memakai inisial nama.
            </p>
          @endif
        </div>
      </div>
    </div>

    <div class="col-lg-8">

      {{-- ==================== Data akun ==================== --}}
      <div class="card mb-4">
        <div class="card-header d-flex align-items-center gap-3">
          <span class="profile-section-icon"><i class="icon-base ti tabler-user"></i></span>
          <div>
            <h5 class="mb-0">Data Akun</h5>
            <p class="mb-0 text-body-secondary small">Nama dan email untuk masuk ke dashboard</p>
          </div>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf @method('PUT')

            <div class="row">
              <div class="col-md-6 mb-4">
                <label class="form-label" for="name">Nama <span class="text-danger">*</span></label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="icon-base ti tabler-user-circle"></i></span>
                  <input type="text" class="form-control @error('name') is-invalid @enderror"
                         id="name" name="name" value="{{ old('name', $user->name) }}" required />
                </div>
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                <div class="form-text">Tampil di pojok kanan atas dashboard.</div>
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="icon-base ti tabler-mail"></i></span>
                  <input type="email" class="form-control @error('email') is-invalid @enderror"
                         id="email" name="email" value="{{ old('email', $user->email) }}" required />
                </div>
                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                <div class="form-text">Dipakai sebagai identitas saat masuk.</div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Perubahan
            </button>
          </form>
        </div>
      </div>

      {{-- ==================== Kata sandi ==================== --}}
      <div class="card" id="kata-sandi">
        <div class="card-header d-flex align-items-center gap-3">
          <span class="profile-section-icon"><i class="icon-base ti tabler-key"></i></span>
          <div>
            <h5 class="mb-0">Ubah Kata Sandi</h5>
            <p class="mb-0 text-body-secondary small">
              Kata sandi saat ini wajib dimasukkan sebagai pengaman
            </p>
          </div>
        </div>
        <div class="card-body">
          <div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
            <i class="icon-base ti tabler-info-circle mt-1"></i>
            <div class="small">
              Setelah kata sandi diganti, sesi di perangkat lain otomatis dikeluarkan.
              Anda tetap masuk di perangkat ini.
            </div>
          </div>

          <form method="POST" action="{{ route('admin.profile.password') }}">
            @csrf @method('PUT')

            <div class="mb-4 form-password-toggle">
              <label class="form-label" for="current_password">
                Kata Sandi Saat Ini <span class="text-danger">*</span>
              </label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="icon-base ti tabler-lock"></i></span>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                       id="current_password" name="current_password" required autocomplete="current-password" />
                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
              </div>
              @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-4 form-password-toggle">
                <label class="form-label" for="password">
                  Kata Sandi Baru <span class="text-danger">*</span>
                </label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="icon-base ti tabler-key"></i></span>
                  <input type="password" class="form-control @error('password') is-invalid @enderror"
                         id="password" name="password" required autocomplete="new-password" />
                  <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                </div>
                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                <div class="form-text">Minimal 8 karakter.</div>
              </div>

              <div class="col-md-6 mb-4 form-password-toggle">
                <label class="form-label" for="password_confirmation">
                  Ulangi Kata Sandi Baru <span class="text-danger">*</span>
                </label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="icon-base ti tabler-key"></i></span>
                  <input type="password" class="form-control"
                         id="password_confirmation" name="password_confirmation" required autocomplete="new-password" />
                  <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-key me-1"></i> Ganti Kata Sandi
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Tombol mata pada setiap isian kata sandi.
  document.querySelectorAll('.form-password-toggle .input-group-text:last-child').forEach(function (tombol) {
    tombol.addEventListener('click', function () {
      const input = this.closest('.input-group').querySelector('input');
      const icon = this.querySelector('i');
      const tampilkan = input.type === 'password';

      input.type = tampilkan ? 'text' : 'password';
      icon.className = 'icon-base ti ' + (tampilkan ? 'tabler-eye' : 'tabler-eye-off');
    });
  });
</script>
@endpush
