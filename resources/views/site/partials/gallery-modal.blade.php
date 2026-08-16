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
