<!DOCTYPE html>
<html lang="id" dir="ltr" data-skin="default" data-bs-theme="light"
      data-assets-path="{{ asset('assets') }}/" data-template="blank-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Masuk &middot; Admin {{ setting('site.name', 'Dekorasi.me') }}</title>

  <link rel="icon" type="image/png" href="{{ upload_url(setting('site.favicon'), asset('img/brand/mark.png')) }}" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Public+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/admin-brand.css') }}?v=2" />
  <link rel="stylesheet" href="{{ asset('css/admin-auth.css') }}?v=2" />
</head>

<body class="auth-page">
  <div class="auth-shell">

    {{-- Panel merek --}}
    <aside class="auth-brand">
      <img src="{{ asset('img/brand/logo.png') }}" alt="{{ setting('site.name', 'Dekorasi.me') }}" />
      <h1>Panel Administrator</h1>
      <p>Kelola slider, layanan, portofolio proyek, dan seluruh konten website dari satu tempat.</p>
      <div class="auth-rule"></div>
    </aside>

    {{-- Form masuk --}}
    <main class="auth-form-side">
      <div class="auth-form">

        <img class="auth-logo-sm" src="{{ asset('img/brand/mark.png') }}"
             alt="{{ setting('site.name', 'Dekorasi.me') }}" />

        <span class="auth-eyebrow">Area Terbatas</span>
        <h2>Selamat datang</h2>
        <p class="auth-sub">Masuk untuk mulai mengelola website {{ setting('site.name', 'Dekorasi.me') }}.</p>

        @if ($errors->any())
          <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="icon-base ti tabler-alert-circle me-2"></i>
            <div>{{ $errors->first() }}</div>
          </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
          @csrf

          <div class="mb-4">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}"
                   placeholder="admin@dekorasi.me" autofocus required autocomplete="username" />
          </div>

          <div class="mb-4 form-password-toggle">
            <label class="form-label" for="password">Kata Sandi</label>
            <div class="input-group input-group-merge">
              <input type="password" id="password" name="password"
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                     required autocomplete="current-password" />
              <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
            </div>
          </div>

          <div class="mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" />
              <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
          </div>

          <button class="btn btn-auth" type="submit">Masuk</button>
        </form>

        <a href="{{ route('home') }}" class="auth-back">
          <i class="icon-base ti tabler-chevron-left"></i>
          <span>Kembali ke website</span>
        </a>
      </div>
    </main>
  </div>

  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

  <script>
    // Tombol mata: tampilkan / sembunyikan kata sandi.
    document.querySelector('.form-password-toggle .input-group-text').addEventListener('click', function () {
      const input = document.getElementById('password');
      const icon = this.querySelector('i');
      const show = input.type === 'password';

      input.type = show ? 'text' : 'password';
      icon.className = 'icon-base ti ' + (show ? 'tabler-eye' : 'tabler-eye-off');
    });
  </script>
</body>
</html>
