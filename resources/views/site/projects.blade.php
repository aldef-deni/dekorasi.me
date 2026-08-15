@extends('layouts.site')

@section('meta-title', __('site.projects.title') . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', __('site.projects.meta_description'))

@section('content')

  <section class="page-hero with-image" style="--hero-image:url('{{ banner_url('projects') }}')">
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">{{ __('site.common.home') }}</a> <span>/</span> <span>{{ __('site.nav.projects') }}</span>
      </div>
      <span class="eyebrow">{{ __('site.projects.eyebrow') }}</span>
      <h1 class="gold-text">{{ __('site.projects.title') }}</h1>
      <p class="lead" style="margin-top:1.2rem">
        {{ __('site.projects.lead') }}
      </p>
    </div>
  </section>

  <section>
    <div class="wrap">

      @if ($categories->isNotEmpty())
        <div class="filters reveal">
          <a href="{{ route('projects.index') }}" class="{{ $active ? '' : 'active' }}">{{ __('site.projects.all') }}</a>
          @foreach ($categories as $category)
            <a href="{{ route('projects.index', ['kategori' => $category['value']]) }}"
               class="{{ $active === $category['value'] ? 'active' : '' }}">{{ $category['label'] }}</a>
          @endforeach
        </div>
      @endif

      @if ($projects->isEmpty())
        <p class="lead" style="text-align:center">
          {{ $active ? __('site.projects.empty_filter') : __('site.projects.empty') }}
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
