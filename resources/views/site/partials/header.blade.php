@php
    $navItems = [
        ['route' => 'home',            'label' => 'Beranda'],
        ['route' => 'about',           'label' => 'Tentang Kami'],
        ['route' => 'services.index',  'label' => 'Paket Layanan',  'pattern' => 'layanan*'],
        ['route' => 'projects.index',  'label' => 'Portofolio','pattern' => 'proyek*'],
        ['route' => 'contact',         'label' => 'Kontak'],
    ];
@endphp

<header class="site-header">
  <div class="wrap">
    <a href="{{ route('home') }}" class="brand">
      <img src="{{ upload_url(setting('site.logo'), asset('img/brand/mark.png')) }}"
           alt="{{ setting('site.name', 'Dekorasi.me') }}" />
      <span>{{ setting('site.name', 'Dekorasi.me') }}</span>
    </a>

    <nav class="nav" id="main-nav">
      {{-- Hanya tampil di mobile: tombol hamburger tertutup drawer saat terbuka --}}
      <button type="button" class="nav-close" aria-label="Tutup menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
          <path d="m6 6 12 12M18 6 6 18" />
        </svg>
      </button>

      @foreach ($navItems as $item)
        @php
            $isActive = isset($item['pattern']) ? request()->is($item['pattern']) : request()->routeIs($item['route']);
        @endphp
        <a href="{{ route($item['route']) }}" class="{{ $isActive ? 'active' : '' }}">{{ $item['label'] }}</a>
      @endforeach
    </nav>

    <button type="button" class="nav-toggle" aria-label="Buka menu"
            aria-controls="main-nav" aria-expanded="false">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
        <path d="M4 7h16M4 12h16M4 17h16" />
      </svg>
    </button>
  </div>
</header>

<div class="nav-backdrop"></div>
