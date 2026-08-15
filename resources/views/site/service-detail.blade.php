@extends('layouts.site')

@section('meta-title', $service->t('title') . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', Str::limit(strip_tags($service->t('excerpt') ?: $service->t('description')), 155))

@section('content')

  @php $fitur = $service->featureList(); @endphp

  <section class="page-hero {{ $service->image ? 'with-image' : '' }}"
           @if ($service->image) style="--hero-image:url('{{ upload_url($service->image) }}')" @endif>
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">{{ __('site.common.home') }}</a> <span>/</span>
        <a href="{{ route('services.index') }}">{{ __('site.nav.services') }}</a> <span>/</span>
        <span>{{ $service->t('title') }}</span>
      </div>
      <span class="eyebrow">{{ $service->subtitle ?: __('site.nav.services') }}</span>
      <h1 class="gold-text">{{ $service->t('title') }}</h1>
      @if ($service->t('excerpt'))
        <p class="lead" style="margin-top:1.2rem">{{ $service->t('excerpt') }}</p>
      @endif
      @if ($service->t('price'))
        <p class="lead" style="margin-top:.6rem;color:var(--gold-deep);font-weight:600">{{ $service->t('price') }}</p>
      @endif
    </div>
  </section>

  <section>
    <div class="wrap">
      <div class="split" style="align-items:start">

        {{-- Isi paket --}}
        @if ($fitur->isNotEmpty())
          <div class="reveal">
            <span class="eyebrow">{{ __('site.services.included') }}</span>
            <ul class="pkg-features" style="margin-top:1.2rem;margin-bottom:0">
              @foreach ($fitur as $poin)
                <li>{{ $poin }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Penjelasan --}}
        <div class="reveal">
          @if ($service->t('description'))
            <span class="eyebrow">{{ __('site.services.about_package') }}</span>
            <div class="prose" style="margin-top:1.2rem">
              @foreach (preg_split('/\R{2,}/', trim($service->t('description'))) as $paragraph)
                @if (trim($paragraph) !== '')
                  <p>{{ $paragraph }}</p>
                @endif
              @endforeach
            </div>
          @elseif ($fitur->isEmpty())
            <p class="lead">{{ __('site.services.detail_empty') }}</p>
          @endif

          @if (setting('contact.whatsapp'))
            <a class="btn btn-gold" style="margin-top:1.4rem"
               href="{{ whatsapp_url(setting('contact.whatsapp'), __('site.cta.wa_package', ['site' => setting('site.name', 'Dekorasi.me'), 'package' => $service->t('title')])) }}"
               target="_blank" rel="noopener">
              {{ __('site.services.ask_package') }}
            </a>
          @endif
        </div>
      </div>

      @if ($others->isNotEmpty())
        <div style="margin-top:80px">
          <div class="section-head reveal" style="margin-bottom:28px">
            <span class="eyebrow">{{ __('site.services.compare') }}</span>
            <h2>{{ __('site.services.other') }}</h2>
          </div>
          <div class="pkg-grid">
            @foreach ($others as $other)
              @include('site.partials.package-card', ['service' => $other])
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </section>

  @include('site.partials.cta')

@endsection
