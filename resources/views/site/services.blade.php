@extends('layouts.site')

@section('meta-title', 'Paket Layanan Desain Interior — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', 'Pilihan paket desain interior yang fleksibel: Silver (design only), Gold (design + furnitur custom), dan Platinum (full turn-key solution).')

@section('content')

  <section class="page-hero with-image" style="--hero-image:url('{{ banner_url('services') }}')">
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a> <span>/</span> <span>Paket Layanan</span>
      </div>
      <span class="eyebrow">Pilih Sesuai Kebutuhan</span>
      <h1 class="gold-text">Paket Layanan</h1>
      <p class="lead" style="margin-top:1.2rem">
        Kami menyediakan pilihan paket fleksibel yang dapat disesuaikan dengan
        kebutuhan dan anggaran proyek Anda.
      </p>
    </div>
  </section>

  <section>
    <div class="wrap">
      @if ($services->isEmpty())
        <p class="lead" style="text-align:center">Daftar paket sedang kami siapkan.</p>
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
