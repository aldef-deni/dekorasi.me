@extends('layouts.site')

@section('meta-title', 'Layanan Desain Interior — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', 'Layanan desain interior lengkap: konsep, visualisasi 3D, furnitur custom, hingga pengawasan pengerjaan.')

@section('content')

  <section class="page-hero">
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a> <span>/</span> <span>Layanan</span>
      </div>
      <span class="eyebrow">Apa yang Kami Kerjakan</span>
      <h1 class="gold-text">Layanan Kami</h1>
      <p class="lead" style="margin-top:1.2rem">
        Dari perencanaan awal hingga ruang siap dihuni — pilih layanan yang sesuai dengan tahap proyek Anda.
      </p>
    </div>
  </section>

  <section>
    <div class="wrap">
      @if ($services->isEmpty())
        <p class="lead" style="text-align:center">Daftar layanan sedang kami siapkan.</p>
      @else
        <div class="grid grid-3">
          @foreach ($services as $service)
            <a href="{{ route('services.show', $service) }}" class="card-service reveal">
              <span class="icon">
                @include('site.partials.service-icon', ['icon' => $service->icon])
              </span>
              <h3>{{ $service->title }}</h3>
              <p>{{ $service->excerpt ?: Str::limit(strip_tags($service->description), 140) }}</p>
              <span class="card-link">
                Selengkapnya
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </span>
            </a>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  @include('site.partials.cta')

@endsection
