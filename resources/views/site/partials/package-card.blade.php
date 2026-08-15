{{--
  Kartu paket layanan.

  @param \App\Models\Service $service
--}}
@php $fitur = $service->featureList(); @endphp

<article class="pkg reveal {{ $service->is_featured ? 'featured' : '' }}">
  @if ($service->is_featured)
    <span class="pkg-badge">{{ __('site.services.popular') }}</span>
  @endif

  <span class="pkg-icon">
    @include('site.partials.service-icon', ['icon' => $service->icon])
  </span>

  <h3 class="pkg-name">{{ $service->t('title') }}</h3>

  @if ($service->t('subtitle'))
    <div class="pkg-sub">{{ $service->t('subtitle') }}</div>
  @endif

  @if ($service->t('excerpt'))
    <p class="pkg-excerpt">{{ $service->t('excerpt') }}</p>
  @endif

  @if ($service->t('price'))
    <div class="pkg-price">{{ $service->t('price') }}</div>
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
    {{ __('site.services.view_detail') }}
  </a>
</article>
