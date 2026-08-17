{{--
  Bagian video untuk halaman detail Proyek dan Properti.

  Video sematan (YouTube / Vimeo) tidak langsung dimuat sebagai iframe, tetapi
  diganti gambar sampul yang baru berubah jadi pemutar setelah diklik. Cara ini
  membuat halaman tetap ringan: tanpa itu, setiap video menarik ratusan KB skrip
  pihak ketiga meski pengunjung tidak menontonnya sama sekali.

  @param \Illuminate\Support\Collection $videos
  @param string $judul   Judul bagian
  @param string $eyebrow Label kecil di atas judul
--}}
<section class="video-section" style="padding-block:clamp(56px,7vw,96px)">
  <div class="wrap">
    <div class="section-head reveal" style="margin-bottom:32px">
      <span class="eyebrow">{{ $eyebrow }}</span>
      <h2>{{ $judul }}</h2>
    </div>

    <div class="video-grid {{ $videos->count() === 1 ? 'is-single' : '' }}">
      @foreach ($videos as $video)
        <figure class="video-item reveal">
          @if ($video->diunggah())
            {{-- preload="metadata": hanya durasi & dimensi yang diambil di awal --}}
            <video controls preload="metadata"
                   @if ($video->posterUrl()) poster="{{ $video->posterUrl() }}" @endif
                   aria-label="{{ $video->judul($loop->iteration) }}">
              <source src="{{ $video->fileUrl() }}" type="video/mp4" />
              {{ __('site.videos.unsupported') }}
            </video>
          @else
            <button type="button" class="video-embed" data-embed="{{ $video->embedUrl() }}"
                    aria-label="{{ __('site.videos.play', ['title' => $video->judul($loop->iteration)]) }}">
              @if ($video->posterUrl())
                <img src="{{ $video->posterUrl() }}" alt="{{ $video->judul($loop->iteration) }}" loading="lazy" />
              @else
                <span class="video-blank" aria-hidden="true"></span>
              @endif
              <span class="video-play" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.5v13l11-6.5-11-6.5z"/></svg>
              </span>
            </button>
          @endif

          @if ($video->t('title'))
            <figcaption>{{ $video->t('title') }}</figcaption>
          @endif
        </figure>
      @endforeach
    </div>
  </div>
</section>

@push('scripts')
<script>
  // Sampul diganti iframe hanya saat pengunjung benar-benar ingin menonton.
  (function () {
    document.querySelectorAll('.video-embed').forEach(function (tombol) {
      tombol.addEventListener('click', function () {
        const bingkai = document.createElement('iframe');
        bingkai.src = tombol.dataset.embed;
        bingkai.title = tombol.getAttribute('aria-label');
        bingkai.loading = 'lazy';
        bingkai.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; fullscreen';
        bingkai.allowFullscreen = true;
        tombol.replaceWith(bingkai);
      });
    });
  })();
</script>
@endpush
