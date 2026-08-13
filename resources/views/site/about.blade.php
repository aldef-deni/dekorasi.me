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

  @php $misi = parse_poin(setting('about.mission')); @endphp

  @if (setting('about.vision') || $misi->isNotEmpty())
    <section style="background:var(--bg-soft);border-block:1px solid var(--line-soft)">
      <div class="wrap">
        <div class="vm-split">

          {{-- Kolom kiri: Visi --}}
          @if (setting('about.vision'))
            <div class="reveal">
              <div class="vm-lead">Arah Kami</div>
              <h2 class="vm-title">Visi</h2>
              <div class="vm-rule"></div>

              <div class="vision">
                <p>{{ setting('about.vision') }}</p>
              </div>
            </div>
          @endif

          {{-- Kolom kanan: Misi --}}
          @if ($misi->isNotEmpty())
            <div class="reveal">
              <div class="vm-lead">Cara Kami Mewujudkannya</div>
              <h2 class="vm-title">Misi</h2>
              <div class="vm-rule"></div>

              <div class="mission-list">
                @foreach ($misi as $poin)
                  <div class="mission-item">
                    <span class="mission-no">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                      <h3>{{ $poin['label'] }}</h3>
                      @if ($poin['teks'])
                        <p>{{ $poin['teks'] }}</p>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif

        </div>
      </div>
    </section>
  @endif

  @include('site.partials.cta')

@endsection
