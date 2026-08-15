@extends('layouts.site')

@section('meta-title', $project->t('title') . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', Str::limit(strip_tags($project->t('excerpt') ?: $project->t('description')), 155))

@section('content')

  <section class="page-hero {{ $project->cover_image ? 'with-image' : '' }}"
           @if ($project->cover_image) style="--hero-image:url('{{ upload_url($project->cover_image) }}')" @endif>
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">{{ __('site.common.home') }}</a> <span>/</span>
        <a href="{{ route('projects.index') }}">{{ __('site.nav.projects') }}</a> <span>/</span>
        <span>{{ $project->t('title') }}</span>
      </div>
      @if ($project->t('category'))
        <span class="eyebrow">{{ $project->t('category') }}</span>
      @endif
      <h1 class="gold-text">{{ $project->t('title') }}</h1>
      @if ($project->t('excerpt'))
        <p class="lead" style="margin-top:1.2rem">{{ $project->t('excerpt') }}</p>
      @endif
    </div>
  </section>

  @php
      $specs = array_filter([
          __('site.projects.client')   => $project->client,
          __('site.projects.location') => $project->location,
          __('site.projects.area')     => $project->t('area'),
          __('site.projects.year')     => $project->year,
          __('site.projects.category') => $project->t('category'),
      ]);
  @endphp

  @if ($specs)
    <section style="padding-block:clamp(40px,5vw,64px) 0">
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

  @if ($project->t('description'))
    <section>
      <div class="wrap">
        <div class="prose reveal">
          <span class="eyebrow">{{ __('site.projects.about_project') }}</span>
          @foreach (preg_split('/\R{2,}/', trim($project->t('description'))) as $paragraph)
            @if (trim($paragraph) !== '')
              <p>{{ $paragraph }}</p>
            @endif
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @if ($project->images->isNotEmpty())
    <section style="padding-block:0 clamp(64px,9vw,120px)">
      <div class="wrap">
        <div class="section-head reveal" style="margin-bottom:32px">
          <span class="eyebrow">{{ __('site.projects.gallery') }}</span>
          <h2>{{ __('site.projects.documentation') }}</h2>
        </div>

        <div class="gallery" id="project-gallery">
          @foreach ($project->images as $image)
            <figure class="reveal" data-full="{{ upload_url($image->path) }}">
              <img src="{{ upload_url($image->path) }}"
                   alt="{{ $image->caption ?: $project->t('title') . ' — ' . __('site.projects.photo', ['number' => $loop->iteration]) }}" loading="lazy" />
            </figure>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @if ($related->isNotEmpty())
    <section style="background:var(--bg-soft);border-block:1px solid var(--line-soft)">
      <div class="wrap">
        <div class="section-head reveal">
          <span class="eyebrow">{{ __('site.projects.related') }}</span>
          <h2>{{ __('site.projects.you_may_like') }}</h2>
        </div>
        <div class="project-grid">
          @foreach ($related as $item)
            @include('site.partials.project-card', ['project' => $item])
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @include('site.partials.cta')

  <div class="lightbox" id="lightbox">
    <button type="button" aria-label="{{ __('site.common.close') }}">&times;</button>
    <img src="" alt="" />
  </div>

@endsection

@push('scripts')
<script>
  // Lightbox sederhana untuk galeri proyek.
  (function () {
    const gallery = document.getElementById('project-gallery');
    const lightbox = document.getElementById('lightbox');
    if (!gallery || !lightbox) return;

    const image = lightbox.querySelector('img');

    const close = function () {
      lightbox.classList.remove('open');
      document.body.style.overflow = '';
    };

    gallery.addEventListener('click', function (event) {
      const figure = event.target.closest('figure');
      if (!figure) return;

      image.src = figure.dataset.full;
      image.alt = figure.querySelector('img').alt;
      lightbox.classList.add('open');
      document.body.style.overflow = 'hidden';
    });

    lightbox.addEventListener('click', close);
    document.addEventListener('keydown', e => e.key === 'Escape' && close());
  })();
</script>
@endpush
