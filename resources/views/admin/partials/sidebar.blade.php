@php
    $menu = [
        ['route' => 'admin.dashboard',     'icon' => 'tabler-smart-home',   'label' => 'Dashboard'],
        ['route' => 'admin.sliders.index', 'icon' => 'tabler-slideshow',    'label' => 'Slider Beranda', 'pattern' => 'admin/sliders*'],
        ['route' => 'admin.services.index','icon' => 'tabler-package',      'label' => 'Paket Layanan',  'pattern' => 'admin/services*'],
        ['route' => 'admin.projects.index','icon' => 'tabler-building-arch','label' => 'Proyek',         'pattern' => 'admin/projects*'],
        ['route' => 'admin.properties.index','icon' => 'tabler-home-dollar','label' => 'Properti',     'pattern' => 'admin/properties*'],
        ['route' => 'admin.about.edit',    'icon' => 'tabler-info-circle',  'label' => 'Tentang Kami',   'pattern' => 'admin/about*'],
        ['route' => 'admin.settings.edit', 'icon' => 'tabler-settings',     'label' => 'Pengaturan Situs','pattern' => 'admin/settings*'],
    ];
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu">
  <div class="app-brand demo">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ upload_url(setting('site.logo'), asset('img/brand/logo.png')) }}" alt="{{ setting('site.name', 'Dekorasi.me') }}" />
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">{{ setting('site.name', 'Dekorasi.me') }}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
      <i class="icon-base ti tabler-x d-block d-xl-none"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <li class="menu-header small">
      <span class="menu-header-text">Manajemen Konten</span>
    </li>

    @foreach ($menu as $item)
      @php
          $isActive = isset($item['pattern']) ? request()->is($item['pattern']) : request()->routeIs($item['route']);
      @endphp
      <li class="menu-item {{ $isActive ? 'active' : '' }}">
        <a href="{{ route($item['route']) }}" class="menu-link">
          <i class="menu-icon icon-base ti {{ $item['icon'] }}"></i>
          <div>{{ $item['label'] }}</div>
        </a>
      </li>
    @endforeach

    <li class="menu-header small">
      <span class="menu-header-text">Situs</span>
    </li>

    <li class="menu-item">
      <a href="{{ route('home') }}" target="_blank" rel="noopener" class="menu-link">
        <i class="menu-icon icon-base ti tabler-external-link"></i>
        <div>Lihat Website</div>
      </a>
    </li>
  </ul>
</aside>
