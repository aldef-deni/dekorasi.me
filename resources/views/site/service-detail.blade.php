@extends('layouts.site')

@section('meta-title', $service->title . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', Str::limit(strip_tags($service->excerpt ?: $service->description), 155))

@section('content')

  <section class="page-hero {{ $service->image ? 'with-image' : '' }}"
           @if ($service->image) style="--hero-image:url('{{ upload_url($service->image) }}')" @endif>
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a> <span>/</span>
        <a href="{{ route('services.index') }}">Layanan</a> <span>/</span>
        <span>{{ $service->title }}</span>
      </div>
      <span class="eyebrow">Layanan</span>
      <h1 class="gold-text">{{ $service->title }}</h1>
      @if ($service->excerpt)
        <p class="lead" style="margin-top:1.2rem">{{ $service->excerpt }}</p>
      @endif
    </div>
  </section>

  <section>
    <div class="wrap">
      <div class="prose reveal">
        @forelse (preg_split('/\R{2,}/', trim((string) $service->description)) as $paragraph)
          @if (trim($paragraph) !== '')
            <p>{{ $paragraph }}</p>
          @endif
        @empty
          <p>Detail layanan ini sedang kami lengkapi. Hubungi kami untuk penjelasan langsung.</p>
        @endforelse
      </div>

      @if ($others->isNotEmpty())
        <div style="margin-top:72px">
          <h3 style="margin-bottom:24px">Layanan Lainnya</h3>
          <div class="grid grid-3">
            @foreach ($others as $other)
              <a href="{{ route('services.show', $other) }}" class="card-service reveal">
                <h3 style="font-size:1.2rem">{{ $other->title }}</h3>
                <p>{{ Str::limit($other->excerpt ?: strip_tags($other->description), 100) }}</p>
                <span class="card-link">
                  Selengkapnya
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
              </a>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </section>

  @include('site.partials.cta')

@endsection
