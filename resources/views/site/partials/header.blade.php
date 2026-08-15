@php
    $navItems = [
        ['route' => 'home',            'label' => __('site.nav.home')],
        ['route' => 'about',           'label' => __('site.nav.about')],
        ['route' => 'services.index',  'label' => __('site.nav.services'),  'pattern' => 'layanan*'],
        ['route' => 'projects.index',  'label' => __('site.nav.projects'),  'pattern' => 'proyek*'],
        ['route' => 'contact',         'label' => __('site.nav.contact')],
    ];

    $bahasa = \App\Http\Middleware\SetLocale::SUPPORTED;
    $aktif  = app()->getLocale();
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
      <button type="button" class="nav-close" aria-label="{{ __('site.nav.close_menu') }}">
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

      {{-- Pemilih bahasa versi mobile, ikut di dalam drawer --}}
      <div class="lang-switch lang-switch-mobile" role="group" aria-label="{{ __('site.nav.language') }}">
        @foreach ($bahasa as $kode => $nama)
          <a href="{{ route('locale.switch', $kode) }}"
             class="{{ $aktif === $kode ? 'active' : '' }}"
             hreflang="{{ $kode }}" title="{{ $nama }}">{{ strtoupper($kode) }}</a>
        @endforeach
      </div>
    </nav>

    <div class="header-right">
      {{-- Pemilih bahasa versi desktop --}}
      <div class="lang-switch" role="group" aria-label="{{ __('site.nav.language') }}">
        @foreach ($bahasa as $kode => $nama)
          <a href="{{ route('locale.switch', $kode) }}"
             class="{{ $aktif === $kode ? 'active' : '' }}"
             hreflang="{{ $kode }}" title="{{ $nama }}">{{ strtoupper($kode) }}</a>
        @endforeach
      </div>

      <button type="button" class="nav-toggle" aria-label="{{ __('site.nav.open_menu') }}"
              aria-controls="main-nav" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
          <path d="M4 7h16M4 12h16M4 17h16" />
        </svg>
      </button>
    </div>
  </div>
</header>

<div class="nav-backdrop"></div>
