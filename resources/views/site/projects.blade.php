@extends('layouts.site')

@section('meta-title', 'Portofolio Proyek — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', 'Kumpulan proyek desain interior yang telah kami kerjakan: residensial, komersial, kantor, dan lainnya.')

@section('content')

  <section class="page-hero">
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a> <span>/</span> <span>Portofolio</span>
      </div>
      <span class="eyebrow">Karya Kami</span>
      <h1 class="gold-text">Portofolio Proyek</h1>
      <p class="lead" style="margin-top:1.2rem">
        Setiap proyek punya cerita, kendala, dan solusinya sendiri. Berikut sebagian yang sudah kami wujudkan.
      </p>
    </div>
  </section>

  <section>
    <div class="wrap">

      @if ($categories->isNotEmpty())
        <div class="filters reveal">
          <a href="{{ route('projects.index') }}" class="{{ $active ? '' : 'active' }}">Semua</a>
          @foreach ($categories as $category)
            <a href="{{ route('projects.index', ['kategori' => $category]) }}"
               class="{{ $active === $category ? 'active' : '' }}">{{ $category }}</a>
          @endforeach
        </div>
      @endif

      @if ($projects->isEmpty())
        <p class="lead" style="text-align:center">
          {{ $active ? 'Belum ada proyek pada kategori ini.' : 'Portofolio sedang kami siapkan.' }}
        </p>
      @else
        <div class="project-grid">
          @foreach ($projects as $project)
            @include('site.partials.project-card', ['project' => $project])
          @endforeach
        </div>

        {{ $projects->links() }}
      @endif
    </div>
  </section>

  @include('site.partials.cta')

@endsection
