{{--
  Galeri foto berhalaman + modal yang bisa digeser.

  Dipakai bersama oleh halaman detail proyek dan detail properti, jadi
  perbaikan di sini langsung berlaku untuk keduanya.

  @param \Illuminate\Support\Collection $images  Koleksi gambar (punya ->path & ->caption)
  @param string $judul                           Dipakai sebagai alt bila caption kosong
  @param int    $perHalaman                      Jumlah foto per halaman (bawaan 9)
--}}
@php
    $perHalaman = $perHalaman ?? 9;
@endphp

<div class="gallery" id="project-gallery" data-per-page="{{ $perHalaman }}">
  @foreach ($images as $image)
    <figure class="reveal"
            data-index="{{ $loop->index }}"
            data-full="{{ upload_url($image->path) }}"
            data-caption="{{ $image->caption }}">
      <img src="{{ upload_url($image->path) }}"
           alt="{{ $image->caption ?: $judul . ' — ' . __('site.projects.photo', ['number' => $loop->iteration]) }}" loading="lazy" />
      <span class="gallery-zoom" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
          <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5M11 8v6M8 11h6"/>
        </svg>
      </span>
    </figure>
  @endforeach
</div>

{{-- Paginasi galeri: dibangun JavaScript, hanya tampil bila lebih dari satu halaman --}}
@if ($images->count() > $perHalaman)
  <nav class="gallery-pager" id="gallery-pager" aria-label="{{ __('site.projects.gallery') }}"></nav>
@endif
