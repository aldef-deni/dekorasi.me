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

        @php $perHalaman = 9; @endphp

        <div class="gallery" id="project-gallery" data-per-page="{{ $perHalaman }}">
          @foreach ($project->images as $image)
            <figure class="reveal"
                    data-index="{{ $loop->index }}"
                    data-full="{{ upload_url($image->path) }}"
                    data-caption="{{ $image->caption }}">
              <img src="{{ upload_url($image->path) }}"
                   alt="{{ $image->caption ?: $project->t('title') . ' — ' . __('site.projects.photo', ['number' => $loop->iteration]) }}" loading="lazy" />
              <span class="gallery-zoom" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                  <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5M11 8v6M8 11h6"/>
                </svg>
              </span>
            </figure>
          @endforeach
        </div>

        {{-- Paginasi galeri: dibangun JavaScript, hanya tampil bila lebih dari satu halaman --}}
        @if ($project->images->count() > $perHalaman)
          <nav class="gallery-pager" id="gallery-pager" aria-label="{{ __('site.projects.gallery') }}"></nav>
        @endif
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

  {{-- Modal galeri: geser kiri-kanan, tombol panah, sapuan jari, dan papan ketik --}}
  <div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="{{ __('site.projects.gallery') }}">
    <button type="button" class="lb-close" data-lb="close" aria-label="{{ __('site.common.close') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
        <path d="m6 6 12 12M18 6 6 18"/>
      </svg>
    </button>

    <button type="button" class="lb-nav lb-prev" data-lb="prev" aria-label="{{ __('site.projects.prev') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
        <path d="m15 5-7 7 7 7"/>
      </svg>
    </button>

    <figure class="lb-stage">
      <img id="lb-image" src="" alt="" />
      <figcaption class="lb-meta">
        <span class="lb-caption" id="lb-caption"></span>
        <span class="lb-counter" id="lb-counter"></span>
      </figcaption>
    </figure>

    <button type="button" class="lb-nav lb-next" data-lb="next" aria-label="{{ __('site.projects.next') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
        <path d="m9 5 7 7-7 7"/>
      </svg>
    </button>
  </div>

@endsection

@push('scripts')
<script>
  /**
   * Galeri proyek: grid berhalaman + modal yang bisa digeser.
   *
   * Paginasi dikerjakan di sisi klien supaya perpindahan halaman terasa
   * seketika dan posisi baca tidak hilang. Modal tetap menelusuri SELURUH
   * foto proyek — bukan hanya halaman yang sedang tampil — dan grid ikut
   * berpindah halaman mengikuti foto yang sedang dibuka.
   */
  (function () {
    const grid = document.getElementById('project-gallery');
    const lightbox = document.getElementById('lightbox');
    if (!grid || !lightbox) return;

    const figures = Array.from(grid.querySelectorAll('figure'));
    const perPage = parseInt(grid.dataset.perPage, 10) || 9;
    const pager = document.getElementById('gallery-pager');
    const totalPages = Math.ceil(figures.length / perPage);

    const gambar  = document.getElementById('lb-image');
    const caption = document.getElementById('lb-caption');
    const counter = document.getElementById('lb-counter');

    const TEKS = {
      prev:    @json(__('site.projects.prev')),
      next:    @json(__('site.projects.next')),
      page:    @json(__('site.projects.page', ['number' => ':n'])),
      counter: @json(__('site.projects.counter', ['current' => ':c', 'total' => ':t'])),
    };

    let halaman = 1;
    let aktif = 0;

    /* ---------------- Paginasi grid ---------------- */

    function tampilkanHalaman(n) {
      halaman = Math.min(Math.max(n, 1), totalPages);
      const awal = (halaman - 1) * perPage;

      figures.forEach(function (fig, i) {
        const tampil = i >= awal && i < awal + perPage;
        fig.hidden = !tampil;
        // Elemen yang baru muncul ikut dianimasikan seperti saat pertama dimuat.
        if (tampil) fig.classList.add('visible');
      });

      gambarPager();
    }

    function tombolPager(label, aria, aktifkan, onClick, nonaktif) {
      const b = document.createElement('button');
      b.type = 'button';
      b.textContent = label;
      if (aria) b.setAttribute('aria-label', aria);
      if (aktifkan) b.classList.add('active');
      if (nonaktif) b.disabled = true;
      b.addEventListener('click', onClick);
      return b;
    }

    function gambarPager() {
      if (!pager || totalPages < 2) return;
      pager.innerHTML = '';

      pager.appendChild(tombolPager('‹', TEKS.prev, false, function () {
        tampilkanHalaman(halaman - 1);
        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, halaman === 1));

      for (let i = 1; i <= totalPages; i++) {
        pager.appendChild(tombolPager(
          String(i),
          TEKS.page.replace(':n', i),
          i === halaman,
          function () {
            tampilkanHalaman(i);
            grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        ));
      }

      pager.appendChild(tombolPager('›', TEKS.next, false, function () {
        tampilkanHalaman(halaman + 1);
        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, halaman === totalPages));
    }

    /* ---------------- Modal galeri ---------------- */

    function tampilkanFoto(i) {
      aktif = (i + figures.length) % figures.length;
      const fig = figures[aktif];

      gambar.src = fig.dataset.full;
      gambar.alt = fig.querySelector('img').alt;

      const teks = fig.dataset.caption || '';
      caption.textContent = teks;
      caption.hidden = teks === '';

      counter.textContent = TEKS.counter
        .replace(':c', aktif + 1)
        .replace(':t', figures.length);

      // Grid ikut pindah halaman agar posisi tetap nyambung saat modal ditutup.
      const halamanFoto = Math.floor(aktif / perPage) + 1;
      if (halamanFoto !== halaman) tampilkanHalaman(halamanFoto);
    }

    function buka(i) {
      tampilkanFoto(i);
      lightbox.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function tutup() {
      lightbox.classList.remove('open');
      document.body.style.overflow = '';
    }

    grid.addEventListener('click', function (e) {
      const fig = e.target.closest('figure');
      if (fig) buka(parseInt(fig.dataset.index, 10));
    });

    lightbox.addEventListener('click', function (e) {
      const aksi = e.target.closest('[data-lb]');

      if (aksi) {
        const jenis = aksi.dataset.lb;
        if (jenis === 'close') tutup();
        if (jenis === 'prev') tampilkanFoto(aktif - 1);
        if (jenis === 'next') tampilkanFoto(aktif + 1);
        return;
      }

      // Klik pada area kosong di luar foto menutup modal.
      if (!e.target.closest('.lb-stage')) tutup();
    });

    document.addEventListener('keydown', function (e) {
      if (!lightbox.classList.contains('open')) return;
      if (e.key === 'Escape') tutup();
      if (e.key === 'ArrowLeft') tampilkanFoto(aktif - 1);
      if (e.key === 'ArrowRight') tampilkanFoto(aktif + 1);
    });

    // Sapuan jari di layar sentuh.
    let sentuhX = null;
    lightbox.addEventListener('touchstart', function (e) {
      sentuhX = e.changedTouches[0].clientX;
    }, { passive: true });

    lightbox.addEventListener('touchend', function (e) {
      if (sentuhX === null) return;
      const jarak = e.changedTouches[0].clientX - sentuhX;
      if (Math.abs(jarak) > 45) tampilkanFoto(aktif + (jarak < 0 ? 1 : -1));
      sentuhX = null;
    }, { passive: true });

    tampilkanHalaman(1);
  })();
</script>
@endpush
