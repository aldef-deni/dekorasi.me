<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
      <i class="icon-base ti tabler-menu-2 icon-md"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
    <ul class="navbar-nav flex-row align-items-center ms-auto">

      <li class="nav-item me-2 d-none d-sm-block">
        <a href="{{ route('home') }}" target="_blank" rel="noopener"
           class="btn btn-sm btn-label-primary d-flex align-items-center gap-1">
          <i class="icon-base ti tabler-external-link icon-16px"></i>
          <span>Lihat Website</span>
        </a>
      </li>

      <!-- Ganti tema terang / gelap -->
      <li class="nav-item me-1">
        <a class="nav-link btn btn-icon btn-text-secondary rounded-pill" href="javascript:void(0);" id="theme-toggle" title="Ganti tema">
          <i class="icon-base ti tabler-sun icon-22px text-heading"></i>
        </a>
      </li>

      <!-- Akun -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="{{ upload_url(auth()->user()->avatar, asset('assets/img/avatars/1.png')) }}"
                 alt="{{ auth()->user()->name }}" class="rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <div class="dropdown-item-text">
              <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-2">
                  <div class="avatar avatar-online">
                    <img src="{{ upload_url(auth()->user()->avatar, asset('assets/img/avatars/1.png')) }}"
                         alt="{{ auth()->user()->name }}" class="rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0 small">{{ auth()->user()->name }}</h6>
                  <small class="text-body-secondary">Administrator</small>
                </div>
              </div>
            </div>
          </li>
          <li><div class="dropdown-divider my-1"></div></li>
          <li>
            <a class="dropdown-item" href="{{ route('admin.settings.edit') }}">
              <i class="icon-base ti tabler-settings me-2 icon-22px"></i>
              <span>Pengaturan Situs</span>
            </a>
          </li>
          <li><div class="dropdown-divider my-1"></div></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item text-danger">
                <i class="icon-base ti tabler-logout me-2 icon-22px"></i>
                <span>Keluar</span>
              </button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

@push('scripts')
<script>
  // Toggle terang/gelap sederhana, tersimpan di localStorage.
  (function () {
    const KEY = 'dekorasi-admin-theme';
    const root = document.documentElement;
    const btn = document.getElementById('theme-toggle');

    const apply = function (theme) {
      root.setAttribute('data-bs-theme', theme);
      const icon = btn.querySelector('i');
      icon.className = 'icon-base ti icon-22px text-heading ' + (theme === 'dark' ? 'tabler-moon' : 'tabler-sun');
    };

    apply(localStorage.getItem(KEY) || 'light');

    btn.addEventListener('click', function () {
      const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
      localStorage.setItem(KEY, next);
      apply(next);
    });
  })();
</script>
@endpush
