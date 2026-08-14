<!DOCTYPE html>
<html
  lang="id"
  class="layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="ltr"
  data-skin="default"
  data-bs-theme="light"
  data-assets-path="{{ asset('assets') }}/"
  data-base-url="{{ url('/') }}"
  data-framework="laravel"
  data-template="vertical-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="robots" content="noindex, nofollow" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>@yield('title', 'Dashboard') &middot; Admin {{ setting('site.name', 'Dekorasi.me') }}</title>

  <link rel="icon" type="image/png" href="{{ upload_url(setting('site.favicon'), asset('img/brand/logo.png')) }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

  <!-- Core CSS (Vuexy) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

  @stack('styles')

  <!-- Penyesuaian merek Dekorasi.me -->
  <link rel="stylesheet" href="{{ asset('css/admin-brand.css') }}?v=4" />

  <!-- Helpers & config wajib dimuat di <head> setelah core CSS -->
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      @include('admin.partials.sidebar')

      <div class="layout-page">
        @include('admin.partials.navbar')

        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
              <div>
                <h4 class="mb-1">@yield('page-title', 'Dashboard')</h4>
                <p class="mb-0 text-body-secondary">@yield('page-subtitle')</p>
              </div>
              <div>@yield('page-actions')</div>
            </div>

            @include('admin.partials.alerts')

            @yield('content')
          </div>

          @include('admin.partials.footer')
          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>

    <div class="layout-overlay layout-menu-toggle"></div>
    <div class="drag-target"></div>
  </div>

  <!-- Vendor JS (Vuexy) -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

  <!-- Theme JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <script>
    // Konfirmasi sebelum menghapus data.
    document.addEventListener('click', function (event) {
      const trigger = event.target.closest('[data-confirm-delete]');
      if (!trigger) return;

      event.preventDefault();
      const form = trigger.closest('form');

      Swal.fire({
        title: 'Hapus data ini?',
        text: trigger.dataset.confirmDelete || 'Data yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-label-secondary ms-2' },
        buttonsStyling: false
      }).then(function (result) {
        if (result.isConfirmed) form.submit();
      });
    });

    // Pratinjau gambar langsung saat file dipilih.
    document.addEventListener('change', function (event) {
      const input = event.target;
      if (!input.matches('input[type="file"][data-preview]')) return;

      const target = document.querySelector(input.dataset.preview);
      const file = input.files && input.files[0];
      if (target && file) {
        target.src = URL.createObjectURL(file);
        target.classList.remove('d-none');
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
