@extends('layouts.site')

@section('meta-title', setting('about.heading', 'Tentang Kami') . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', Str::limit(strip_tags(setting('about.subtitle') ?: setting('about.body')), 155))

@section('content')

  <section class="page-hero {{ setting('about.image') ? 'with-image' : '' }}"
           @if (setting('about.image')) style="--hero-image:url('{{ upload_url(setting('about.image')) }}')" @endif>
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a> <span>/</span> <span>Tentang Kami</span>
      </div>
      <span class="eyebrow">Profil Perusahaan</span>
      <h1 class="gold-text">{{ setting('about.heading', 'Tentang ' . setting('site.name', 'Dekorasi.me')) }}</h1>
      @if (setting('about.subtitle'))
        <p class="lead" style="margin-top:1.2rem">{{ setting('about.subtitle') }}</p>
      @endif
    </div>
  </section>

  <section>
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
          <span class="eyebrow">Cerita Kami</span>
          <div class="prose" style="margin-top:.6rem">
            @forelse (preg_split('/\R{2,}/', trim((string) setting('about.body'))) as $paragraph)
              @if (trim($paragraph) !== '')
                <p>{{ $paragraph }}</p>
              @endif
            @empty
              <p>
                Kami adalah studio desain interior yang percaya bahwa ruang yang baik lahir dari
                mendengarkan. Setiap proyek dimulai dari memahami cara Anda hidup dan bekerja,
                lalu menerjemahkannya menjadi tata ruang, material, dan pencahayaan yang tepat.
              </p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </section>

  @php
      $stats = collect(range(1, 4))
          ->map(fn ($i) => ['value' => setting("about.stat{$i}_value"), 'label' => setting("about.stat{$i}_label")])
          ->filter(fn ($stat) => filled($stat['value']));
  @endphp

  @if ($stats->isNotEmpty())
    <section style="padding-block:0">
      <div class="wrap">
        <div class="stats reveal">
          @foreach ($stats as $stat)
            <div class="stat">
              <div class="value gold-text">{{ $stat['value'] }}</div>
              <div class="label">{{ $stat['label'] }}</div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @if (setting('about.vision') || setting('about.mission'))
    <section>
      <div class="wrap">
        <div class="grid grid-2">
          @if (setting('about.vision'))
            <div class="card-service reveal">
              <span class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" width="26" height="26">
                  <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"/><circle cx="12" cy="12" r="2.6"/>
                </svg>
              </span>
              <h3>Visi</h3>
              <p style="font-size:1rem">{{ setting('about.vision') }}</p>
            </div>
          @endif

          @if (setting('about.mission'))
            <div class="card-service reveal">
              <span class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" width="26" height="26">
                  <circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1" fill="currentColor"/>
                </svg>
              </span>
              <h3>Misi</h3>
              <ul style="color:var(--muted);font-size:.95rem;padding-inline-start:1.1rem;margin:0">
                @foreach (preg_split('/\R+/', trim(setting('about.mission'))) as $mission)
                  @if (trim($mission) !== '')
                    <li style="margin-bottom:.5rem">{{ trim($mission) }}</li>
                  @endif
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </div>
    </section>
  @endif

  @include('site.partials.cta')

@endsection
