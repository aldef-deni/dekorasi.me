@extends('layouts.site')

@section('meta-title', __('site.properties.title') . ' — ' . setting('site.name', 'Dekorasi.me'))
@section('meta-description', __('site.properties.meta_description'))

@section('content')

  <section class="page-hero with-image" style="--hero-image:url('{{ banner_url('properties') }}')">
    <div class="wrap">
      <div class="breadcrumbs">
        <a href="{{ route('home') }}">{{ __('site.common.home') }}</a> <span>/</span> <span>{{ __('site.nav.properties') }}</span>
      </div>
      <span class="eyebrow">{{ __('site.properties.eyebrow') }}</span>
      <h1 class="gold-text">{{ __('site.properties.title') }}</h1>
      <p class="lead" style="margin-top:1.2rem">
        {{ __('site.properties.lead') }}
      </p>
    </div>
  </section>

  <section>
    <div class="wrap">

      @if ($types->isNotEmpty() || $statuses->count() > 1)
        <div class="property-filters reveal">
          @if ($types->isNotEmpty())
            <div class="filter-row">
              <span class="filter-label">{{ __('site.properties.filter_type') }}</span>
              <div class="filters">
                <a href="{{ route('properties.index', array_filter(['status' => $activeStatus])) }}"
                   class="{{ $activeType ? '' : 'active' }}">{{ __('site.properties.all') }}</a>
                @foreach ($types as $type)
                  <a href="{{ route('properties.index', array_filter(['jenis' => $type['value'], 'status' => $activeStatus])) }}"
                     class="{{ $activeType === $type['value'] ? 'active' : '' }}">{{ $type['label'] }}</a>
                @endforeach
              </div>
            </div>
          @endif

          @if ($statuses->count() > 1)
            <div class="filter-row">
              <span class="filter-label">{{ __('site.properties.filter_status') }}</span>
              <div class="filters">
                <a href="{{ route('properties.index', array_filter(['jenis' => $activeType])) }}"
                   class="{{ $activeStatus ? '' : 'active' }}">{{ __('site.properties.all_status') }}</a>
                @foreach ($statuses as $status)
                  <a href="{{ route('properties.index', array_filter(['jenis' => $activeType, 'status' => $status['value']])) }}"
                     class="{{ $activeStatus === $status['value'] ? 'active' : '' }}">{{ $status['label'] }}</a>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      @endif

      @if ($properties->isEmpty())
        <p class="lead" style="text-align:center">
          {{ ($activeType || $activeStatus) ? __('site.properties.empty_filter') : __('site.properties.empty') }}
        </p>
      @else
        <div class="property-grid">
          @foreach ($properties as $property)
            @include('site.partials.property-card', ['property' => $property])
          @endforeach
        </div>

        {{ $properties->links() }}
      @endif
    </div>
  </section>

  @include('site.partials.cta')

@endsection
