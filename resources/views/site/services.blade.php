@extends('layouts.site')

@section('meta-title', __('site.services.title') . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', __('site.services.meta_description'))

@section('content')

  <section class="page-hero with-image" style="--hero-image:url('{{ banner_url('services') }}')">
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">{{ __('site.common.home') }}</a> <span>/</span> <span>{{ __('site.nav.services') }}</span>
      </div>
      <span class="eyebrow">{{ __('site.services.eyebrow') }}</span>
      <h1 class="gold-text">{{ __('site.services.title') }}</h1>
      <p class="lead" style="margin-top:1.2rem">
        {{ __('site.services.lead') }}
      </p>
    </div>
  </section>

  <section>
    <div class="wrap">
      @if ($services->isEmpty())
        <p class="lead" style="text-align:center">{{ __('site.services.empty') }}</p>
      @else
        <div class="pkg-grid">
          @foreach ($services as $service)
            @include('site.partials.package-card', ['service' => $service])
          @endforeach
        </div>

        <p class="lead reveal" style="text-align:center;margin:48px auto 0">
          Belum yakin paket mana yang cocok? Ceritakan kebutuhan Anda —
          kami bantu memetakan prioritas dan anggarannya.
        </p>
      @endif
    </div>
  </section>

  @include('site.partials.cta')

@endsection
