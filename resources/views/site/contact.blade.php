@extends('layouts.site')

@section('meta-title', 'Kontak — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', 'Hubungi kami untuk konsultasi desain interior. Alamat, telepon, email, dan WhatsApp.')

@section('content')

  <section class="page-hero with-image" style="--hero-image:url('{{ banner_url('contact') }}')">
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a> <span>/</span> <span>Kontak</span>
      </div>
      <span class="eyebrow">Mari Bicara</span>
      <h1 class="gold-text">Hubungi Kami</h1>
      <p class="lead" style="margin-top:1.2rem">
        Ceritakan ruang yang ingin Anda ubah. Kami balas secepatnya di jam kerja.
      </p>
    </div>
  </section>

  <section>
    <div class="wrap">
      @php
          $siteName = setting('site.name', 'Dekorasi.me');
          $channels = array_filter([
              ['label' => 'Alamat',   'value' => setting('contact.address'), 'href' => null,  'icon' => 'pin'],
              ['label' => 'Telepon',  'value' => setting('contact.phone'),   'href' => setting('contact.phone') ? 'tel:'.preg_replace('/\s+/', '', setting('contact.phone')) : null, 'icon' => 'phone'],
              ['label' => 'Email',    'value' => setting('contact.email'),   'href' => setting('contact.email') ? 'mailto:'.setting('contact.email') : null, 'icon' => 'mail'],
              ['label' => 'WhatsApp', 'value' => setting('contact.whatsapp'),'href' => setting('contact.whatsapp') ? whatsapp_url(setting('contact.whatsapp'), 'Halo '.$siteName.', saya ingin konsultasi desain interior.') : null, 'icon' => 'wa'],
              ['label' => 'Jam Operasional', 'value' => setting('contact.hours'), 'href' => null, 'icon' => 'clock'],
          ], fn ($channel) => filled($channel['value']));
      @endphp

      @if ($channels)
        <div class="grid grid-3">
          @foreach ($channels as $channel)
            <div class="card-service reveal">
              <span class="icon">
                @switch($channel['icon'])
                  @case('pin')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" width="24" height="24"><path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11Z"/><circle cx="12" cy="10" r="2.6"/></svg>
                    @break
                  @case('phone')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" width="24" height="24"><path d="M5 3h3l2 5-2.5 1.5a12 12 0 0 0 6 6L15 13l5 2v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 3 5.2 2 2 0 0 1 5 3Z"/></svg>
                    @break
                  @case('mail')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" width="24" height="24"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3.5 7.5 8.5 6 8.5-6"/></svg>
                    @break
                  @case('wa')
                    <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3.1.8.8-3-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.1-.2 0-.4.1-.5l.5-.6c.1-.2.1-.3 0-.5l-.8-1.8c-.2-.4-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.2.3-.9.9-.9 2.2s.9 2.5 1.1 2.7c.1.2 1.8 2.8 4.4 3.9 1.6.7 2.2.7 3 .6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2Z"/></svg>
                    @break
                  @default
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" width="24" height="24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                @endswitch
              </span>
              <h3 style="font-size:1.15rem">{{ $channel['label'] }}</h3>
              @if ($channel['href'])
                <p style="margin-bottom:0">
                  <a href="{{ $channel['href'] }}" @if (Str::startsWith($channel['href'], 'http')) target="_blank" rel="noopener" @endif
                     style="color:var(--gold-lite)">{{ $channel['value'] }}</a>
                </p>
              @else
                <p style="margin-bottom:0">{{ $channel['value'] }}</p>
              @endif
            </div>
          @endforeach
        </div>
      @else
        <p class="lead" style="text-align:center">
          Informasi kontak sedang dilengkapi. Silakan cek kembali sebentar lagi.
        </p>
      @endif

      @php $peta = maps_embed_url(); @endphp

      @if ($peta)
        <div class="map-block reveal" id="peta">
          <iframe src="{{ $peta }}" title="Lokasi {{ $siteName }}"
                  loading="lazy" allowfullscreen
                  referrerpolicy="no-referrer-when-downgrade"></iframe>

          {{-- Kartu alamat mengambang di atas peta --}}
          <div class="map-card">
            <span class="eyebrow">Lokasi Kami</span>
            <h3>{{ $siteName }}</h3>

            @if (setting('contact.address'))
              <p class="map-address">{{ setting('contact.address') }}</p>
            @endif

            @if (setting('contact.hours'))
              <p class="map-hours">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="15" height="15">
                  <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
                </svg>
                {{ setting('contact.hours') }}
              </p>
            @endif

            <div class="map-actions">
              <a class="btn btn-gold" href="{{ maps_link_url(true) }}" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" width="16" height="16">
                  <path d="m3 11 18-8-8 18-2-8-8-2Z"/>
                </svg>
                Petunjuk Arah
              </a>
              <a class="btn btn-ghost" href="{{ maps_link_url() }}" target="_blank" rel="noopener">
                Buka di Maps
              </a>
            </div>
          </div>
        </div>
      @endif
    </div>
  </section>

  @include('site.partials.cta')

@endsection
