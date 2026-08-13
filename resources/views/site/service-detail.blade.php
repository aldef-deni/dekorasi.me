@extends('layouts.site')

@section('meta-title', $service->title . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', Str::limit(strip_tags($service->excerpt ?: $service->description), 155))

@section('content')

  @php $fitur = $service->featureList(); @endphp

  <section class="page-hero {{ $service->image ? 'with-image' : '' }}"
           @if ($service->image) style="--hero-image:url('{{ upload_url($service->image) }}')" @endif>
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a> <span>/</span>
        <a href="{{ route('services.index') }}">Paket Layanan</a> <span>/</span>
        <span>{{ $service->title }}</span>
      </div>
      <span class="eyebrow">{{ $service->subtitle ?: 'Paket Layanan' }}</span>
      <h1 class="gold-text">{{ $service->title }}</h1>
      @if ($service->excerpt)
        <p class="lead" style="margin-top:1.2rem">{{ $service->excerpt }}</p>
      @endif
      @if ($service->price)
        <p class="lead" style="margin-top:.6rem;color:var(--gold-deep);font-weight:600">{{ $service->price }}</p>
      @endif
    </div>
  </section>

  <section>
    <div class="wrap">
      <div class="split" style="align-items:start">

        {{-- Isi paket --}}
        @if ($fitur->isNotEmpty())
          <div class="reveal">
            <span class="eyebrow">Yang Anda Dapatkan</span>
            <ul class="pkg-features" style="margin-top:1.2rem;margin-bottom:0">
              @foreach ($fitur as $poin)
                <li>{{ $poin }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Penjelasan --}}
        <div class="reveal">
          @if ($service->description)
            <span class="eyebrow">Tentang Paket Ini</span>
            <div class="prose" style="margin-top:1.2rem">
              @foreach (preg_split('/\R{2,}/', trim($service->description)) as $paragraph)
                @if (trim($paragraph) !== '')
                  <p>{{ $paragraph }}</p>
                @endif
              @endforeach
            </div>
          @elseif ($fitur->isEmpty())
            <p class="lead">Detail paket ini sedang kami lengkapi. Hubungi kami untuk penjelasan langsung.</p>
          @endif

          @if (setting('contact.whatsapp'))
            <a class="btn btn-gold" style="margin-top:1.4rem"
               href="{{ whatsapp_url(setting('contact.whatsapp'), 'Halo ' . setting('site.name', 'Dekorasi.me') . ', saya tertarik dengan ' . $service->title . '.') }}"
               target="_blank" rel="noopener">
              Tanya Paket Ini
            </a>
          @endif
        </div>
      </div>

      @if ($others->isNotEmpty())
        <div style="margin-top:80px">
          <div class="section-head reveal" style="margin-bottom:28px">
            <span class="eyebrow">Bandingkan</span>
            <h2>Paket Lainnya</h2>
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
