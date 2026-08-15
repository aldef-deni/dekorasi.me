@extends('layouts.site')

@section('meta-title', setting_t('about.heading', __('site.nav.about')) . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', Str::limit(strip_tags(setting_t('about.subtitle') ?: setting_t('about.body')), 155))

@section('content')

  <section class="page-hero with-image" style="--hero-image:url('{{ banner_url('about') }}')">
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">{{ __('site.common.home') }}</a> <span>/</span> <span>{{ __('site.nav.about') }}</span>
      </div>
      <span class="eyebrow">{{ __('site.about.eyebrow') }}</span>
      <h1 class="gold-text">{{ setting_t('about.heading', __('site.about.default_heading')) }}</h1>
      @if (setting_t('about.subtitle'))
        <p class="lead" style="margin-top:1.2rem">{{ setting_t('about.subtitle') }}</p>
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
          <span class="eyebrow">{{ __('site.about.story') }}</span>
          <div class="prose" style="margin-top:.6rem">
            @forelse (preg_split('/\R{2,}/', trim((string) setting_t('about.body'))) as $paragraph)
              @if (trim($paragraph) !== '')
                <p>{{ $paragraph }}</p>
              @endif
            @empty
              <p>{{ __('site.about.default_body') }}</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </section>

  @php
      $stats = collect(range(1, 4))
          ->map(fn ($i) => ['value' => setting("about.stat{$i}_value"), 'label' => setting_t("about.stat{$i}_label")])
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

  @php $misi = parse_poin(setting_t('about.mission')); @endphp

  @if (setting('about.vision') || $misi->isNotEmpty())
    <section style="background:var(--bg-soft);border-block:1px solid var(--line-soft)">
      <div class="wrap">
        <div class="vm-split">

          {{-- Kolom kiri: Visi --}}
          @if (setting_t('about.vision'))
            <div class="reveal">
              <div class="vm-lead">{{ __('site.about.direction') }}</div>
              <h2 class="vm-title">{{ __('site.about.vision') }}</h2>
              <div class="vm-rule"></div>

              <div class="vision">
                <p>{{ setting_t('about.vision') }}</p>
              </div>
            </div>
          @endif

          {{-- Kolom kanan: Misi --}}
          @if ($misi->isNotEmpty())
            <div class="reveal">
              <div class="vm-lead">{{ __('site.about.mission_lead') }}</div>
              <h2 class="vm-title">{{ __('site.about.mission') }}</h2>
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

  {{-- ============================ Group Kami ============================ --}}
  @include('site.partials.group')

@endsection
