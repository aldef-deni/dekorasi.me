@php
    // Ikon spesifikasi digambar inline supaya tidak ada permintaan berkas tambahan.
    $ikon = [
        'bed'  => '<path d="M3 18v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6"/><path d="M3 14h18M6 10V7a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v3M13 10V7a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v3M3 18v2M21 18v2"/>',
        'bath' => '<path d="M4 12h16v3a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4v-3z"/><path d="M6 12V6a2 2 0 0 1 4 0M7 19l-1 2M17 19l1 2"/>',
        'area' => '<path d="M4 4h16v16H4z"/><path d="M4 9h5V4M20 15h-5v5"/>',
    ];
@endphp

<a href="{{ route('properties.show', $property) }}" class="property-card reveal">
  <div class="property-media">
    <img src="{{ upload_url($property->cover_image, asset('img/placeholder.svg')) }}"
         alt="{{ $property->t('title') }}" loading="lazy" />

    <span class="property-status {{ $property->sudahTerjual() ? 'is-done' : '' }}">
      {{ $property->labelStatus() }}
    </span>

    @if ($property->type)
      <span class="property-type">{{ $property->t('type') }}</span>
    @endif
  </div>

  <div class="property-body">
    <span class="property-price">{{ $property->hargaRingkas() }}</span>
    <h3>{{ $property->t('title') }}</h3>

    @if ($property->location)
      <span class="property-loc">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
          <path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z" /><circle cx="12" cy="10" r="2.6" />
        </svg>
        {{ $property->t('location') }}
      </span>
    @endif

    @php $spek = $property->ringkasanSpek(); @endphp
    @if ($spek)
      <ul class="property-specs">
        @foreach ($spek as $item)
          <li title="{{ $item['label'] }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              {!! $ikon[$item['ikon']] !!}
            </svg>
            <span>{{ $item['nilai'] }}</span>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</a>
