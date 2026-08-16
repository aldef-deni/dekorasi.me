@extends('layouts.site')

@section('meta-title', $property->t('title') . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', Str::limit(strip_tags($property->t('excerpt') ?: $property->t('description')), 155))

@section('content')

  <section class="page-hero {{ $property->cover_image ? 'with-image' : '' }}"
           @if ($property->cover_image) style="--hero-image:url('{{ upload_url($property->cover_image) }}')" @endif>
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">{{ __('site.common.home') }}</a> <span>/</span>
        <a href="{{ route('properties.index') }}">{{ __('site.nav.properties') }}</a> <span>/</span>
        <span>{{ $property->t('title') }}</span>
      </div>
      @if ($property->t('type'))
        <span class="eyebrow">{{ $property->t('type') }}</span>
      @endif
      <h1 class="gold-text">{{ $property->t('title') }}</h1>
      @if ($property->t('excerpt'))
        <p class="lead" style="margin-top:1.2rem">{{ $property->t('excerpt') }}</p>
      @endif
    </div>
  </section>

  {{-- Panel harga: bagian yang paling dicari, jadi diletakkan paling atas --}}
  <section style="padding-block:clamp(40px,5vw,64px) 0">
    <div class="wrap">
      <div class="price-panel reveal">
        <div class="price-main">
          <span class="price-label">{{ __('site.properties.price') }}</span>
          <strong class="price-value">{{ $property->hargaTampil() }}</strong>
          <span class="price-badge {{ $property->sudahTerjual() ? 'is-done' : '' }}">
            {{ $property->labelStatus() }}
          </span>
        </div>

        <div class="price-side">
          @if ($property->sudahTerjual())
            <p class="price-note">{{ __('site.properties.sold_note') }}</p>
          @elseif (setting('contact.whatsapp'))
            <a class="btn btn-gold"
               href="{{ whatsapp_url(setting('contact.whatsapp'), __('site.properties.ask_message', ['title' => $property->t('title')])) }}"
               target="_blank" rel="noopener">
              {{ __('site.properties.ask') }}
            </a>
          @else
            <a class="btn btn-gold" href="{{ route('contact') }}">{{ __('site.properties.ask') }}</a>
          @endif
        </div>
      </div>
    </div>
  </section>

  @php
      $specs = array_filter([
          __('site.properties.type')          => $property->t('type'),
          __('site.properties.location')      => $property->t('location'),
          __('site.properties.land_area')     => $property->land_area ? $property->land_area . ' m²' : null,
          __('site.properties.building_area') => $property->building_area ? $property->building_area . ' m²' : null,
          __('site.properties.bedrooms')      => $property->bedrooms,
          __('site.properties.bathrooms')     => $property->bathrooms,
          __('site.properties.carports')      => $property->carports,
          __('site.properties.floors')        => $property->floors,
          __('site.properties.certificate')   => $property->t('certificate'),
          __('site.properties.year_built')    => $property->year_built,
      ]);
  @endphp

  @if ($specs)
    <section style="padding-block:clamp(32px,4vw,48px) 0">
      <div class="wrap">
        <dl class="spec-list reveal">
          @foreach ($specs as $label => $value)
            <div>
              <dt>{{ $label }}</dt>
              <dd>{{ $value }}</dd>
            </div>
          @endforeach
        </dl>
      </div>
    </section>
  @endif

  @if ($property->t('description') || $property->address)
    <section>
      <div class="wrap">
        <div class="prose reveal">
          <span class="eyebrow">{{ __('site.properties.about') }}</span>

          @if ($property->t('description'))
            @foreach (preg_split('/\R{2,}/', trim($property->t('description'))) as $paragraph)
              @if (trim($paragraph) !== '')
                <p>{{ $paragraph }}</p>
              @endif
            @endforeach
          @endif

          @if ($property->address)
            <h3>{{ __('site.properties.address') }}</h3>
            <p>{{ $property->address }}</p>
          @endif
        </div>
      </div>
    </section>
  @endif

  @if ($property->images->isNotEmpty())
    <section style="padding-block:0 clamp(64px,9vw,120px)">
      <div class="wrap">
        <div class="section-head reveal" style="margin-bottom:32px">
          <span class="eyebrow">{{ __('site.properties.gallery') }}</span>
          <h2>{{ $property->t('title') }}</h2>
        </div>

        @include('site.partials.gallery', [
            'images' => $property->images,
            'judul'  => $property->t('title'),
        ])
      </div>
    </section>
  @endif

  @if ($related->isNotEmpty())
    <section style="background:var(--bg-soft);border-block:1px solid var(--line-soft)">
      <div class="wrap">
        <div class="section-head reveal">
          <span class="eyebrow">{{ __('site.properties.related') }}</span>
          <h2>{{ __('site.properties.you_may_like') }}</h2>
        </div>
        <div class="property-grid">
          @foreach ($related as $item)
            @include('site.partials.property-card', ['property' => $item])
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @include('site.partials.cta')

  @include('site.partials.gallery-modal')

@endsection

@push('scripts')
  @include('site.partials.gallery-script')
@endpush
