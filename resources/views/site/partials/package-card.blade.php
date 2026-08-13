{{--
  Kartu paket layanan.

  @param \App\Models\Service $service
--}}
@php $fitur = $service->featureList(); @endphp

<article class="pkg reveal {{ $service->is_featured ? 'featured' : '' }}">
  @if ($service->is_featured)
    <span class="pkg-badge">Paling Diminati</span>
  @endif

  <span class="pkg-icon">
    @include('site.partials.service-icon', ['icon' => $service->icon])
  </span>

  <h3 class="pkg-name">{{ $service->title }}</h3>

  @if ($service->subtitle)
    <div class="pkg-sub">{{ $service->subtitle }}</div>
  @endif

  @if ($service->excerpt)
    <p class="pkg-excerpt">{{ $service->excerpt }}</p>
  @endif

  @if ($service->price)
    <div class="pkg-price">{{ $service->price }}</div>
  @endif

  @if ($fitur->isNotEmpty())
    <ul class="pkg-features">
      @foreach ($fitur as $poin)
        <li>{{ $poin }}</li>
      @endforeach
    </ul>
  @endif

  <a href="{{ route('services.show', $service) }}"
     class="btn {{ $service->is_featured ? 'btn-gold' : 'btn-ghost' }}">
    Lihat Detail Paket
  </a>
</article>
