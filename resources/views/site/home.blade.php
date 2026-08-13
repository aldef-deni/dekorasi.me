@extends('layouts.site')

@section('meta-title', setting('seo.title') ?: setting('site.name', 'Dekorasi.me') . ' — ' . setting('site.tagline', 'Desain Interior Premium'))

@section('content')

  {{-- ============================ Hero ============================ --}}
  <section class="hero">
    {{-- Slide tanpa gambar memakai latar dekoratif, bukan gambar rusak. --}}
    @forelse ($sliders as $slider)
      @if ($slider->image)
        <div class="hero-slide {{ $loop->first ? 'active' : '' }}"
             style="--slide-image: url('{{ upload_url($slider->image) }}')"></div>
      @else
        <div class="hero-slide hero-fallback {{ $loop->first ? 'active' : '' }}"></div>
      @endif
    @empty
      <div class="hero-slide hero-fallback active"></div>
    @endforelse

    <div class="wrap hero-inner">
      @php $first = $sliders->first(); @endphp

      <span class="eyebrow">{{ $first->subtitle ?? setting('site.tagline', 'Desain Interior Premium') }}</span>

      <h1 class="gold-text">{{ $first->title ?? 'Ruang yang Bercerita Tentang Anda' }}</h1>

      <p class="lead">
        {{ $first->description ?? setting('site.description', 'Kami merancang interior yang bukan sekadar indah dipandang, tapi nyaman dihuni — dari konsep, visualisasi 3D, hingga eksekusi di lapangan.') }}
      </p>

      <div class="hero-actions">
        <a class="btn btn-gold" href="{{ $first?->cta_url ?: route('projects.index') }}">
          {{ $first?->cta_label ?: 'Lihat Portofolio' }}
        </a>
        <a class="btn btn-ghost" href="{{ route('contact') }}">Konsultasi Gratis</a>
      </div>
    </div>

    @if ($sliders->count() > 1)
      <div class="hero-dots">
        @foreach ($sliders as $slider)
          <button type="button" class="{{ $loop->first ? 'active' : '' }}"
                  aria-label="Slide {{ $loop->iteration }}"></button>
        @endforeach
      </div>
    @endif
  </section>

  {{-- ============================ Paket layanan ============================ --}}
  @if ($services->isNotEmpty())
    <section id="layanan">
      <div class="wrap">
        <div class="section-head center reveal">
          <span class="eyebrow">Pilih Sesuai Kebutuhan</span>
          <h2>Paket <span class="gold-text">Layanan</span></h2>
          <p class="lead" style="margin-inline:auto">
            Pilihan paket fleksibel yang dapat disesuaikan dengan kebutuhan dan
            anggaran proyek Anda.
          </p>
        </div>

        <div class="pkg-grid">
          @foreach ($services as $service)
            @include('site.partials.package-card', ['service' => $service])
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- ============================ Tentang singkat ============================ --}}
  <section style="background:var(--bg-soft);border-block:1px solid var(--line-soft)">
    <div class="wrap">
      <div class="split">
        <div class="split-media stacked reveal">
          <div class="primary">
            <img src="{{ upload_url(setting('about.image'), asset('img/about-dekorasi.jpg')) }}"
                 alt="{{ setting('site.name', 'Dekorasi.me') }}" loading="lazy" />
          </div>
          @if (setting('about.image_secondary'))
            <div class="secondary">
              <img src="{{ upload_url(setting('about.image_secondary')) }}" alt="" loading="lazy" />
            </div>
          @endif
        </div>

        <div class="reveal">
          <span class="eyebrow">Tentang Kami</span>
          <h2>{{ setting('about.heading', 'Detail Kecil, Dampak Besar') }}</h2>
          <p class="lead" style="margin-block:1.2rem 1.6rem">
            {{ setting('about.subtitle', 'Kami percaya interior yang baik lahir dari mendengarkan — memahami cara Anda hidup, bekerja, dan menerima tamu, lalu menerjemahkannya menjadi ruang.') }}
          </p>

          @php
              $stats = collect(range(1, 4))
                  ->map(fn ($i) => ['value' => setting("about.stat{$i}_value"), 'label' => setting("about.stat{$i}_label")])
                  ->filter(fn ($stat) => filled($stat['value']));
          @endphp

          @if ($stats->isNotEmpty())
            <div class="stats" style="margin-bottom:2rem">
              @foreach ($stats as $stat)
                <div class="stat">
                  <div class="value gold-text">{{ $stat['value'] }}</div>
                  <div class="label">{{ $stat['label'] }}</div>
                </div>
              @endforeach
            </div>
          @endif

          <a class="btn btn-ghost" href="{{ route('about') }}">Kenali Kami Lebih Jauh</a>
        </div>
      </div>
    </div>
  </section>

  {{-- ============================ Portofolio unggulan ============================ --}}
  @if ($projects->isNotEmpty())
    <section id="portofolio">
      <div class="wrap">
        <div class="section-head center reveal">
          <span class="eyebrow">Portofolio Pilihan</span>
          <h2>Proyek yang Sudah <span class="gold-text">Kami Wujudkan</span></h2>
        </div>

        <div class="project-grid">
          @foreach ($projects as $project)
            @include('site.partials.project-card', ['project' => $project])
          @endforeach
        </div>

        <div style="text-align:center;margin-top:48px" class="reveal">
          <a class="btn btn-ghost" href="{{ route('projects.index') }}">Lihat Semua Proyek</a>
        </div>
      </div>
    </section>
  @endif

  {{-- ============================ CTA ============================ --}}
  @include('site.partials.cta')

@endsection

@push('scripts')
@if ($sliders->count() > 1)
<script>
  // Pergantian slide hero otomatis, bisa juga diklik lewat indikator.
  (function () {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dots button');
    if (slides.length < 2) return;

    let index = 0;
    let timer;

    const show = function (next) {
      slides[index].classList.remove('active');
      dots[index].classList.remove('active');
      index = (next + slides.length) % slides.length;
      slides[index].classList.add('active');
      dots[index].classList.add('active');
    };

    const start = function () {
      timer = setInterval(() => show(index + 1), 6000);
    };

    dots.forEach(function (dot, i) {
      dot.addEventListener('click', function () {
        clearInterval(timer);
        show(i);
        start();
      });
    });

    start();
  })();
</script>
@endif
@endpush
