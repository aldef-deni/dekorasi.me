@php $siteName = setting('site.name', 'Dekorasi.me'); @endphp

<section class="cta">
  <div class="wrap reveal">
    <span class="eyebrow" style="justify-content:center">{{ __('site.cta.eyebrow') }}</span>
    <h2>{!! __('site.cta.title', ['highlight' => '<span class="gold-text">'.__('site.cta.highlight').'</span>']) !!}</h2>
    <p class="lead">
      {{ __('site.cta.lead') }}
    </p>

    <div style="display:flex;flex-wrap:wrap;gap:14px;justify-content:center">
      @if (setting('contact.whatsapp'))
        <a class="btn btn-gold"
           href="{{ whatsapp_url(setting('contact.whatsapp'), __('site.cta.wa_message', ['site' => $siteName])) }}"
           target="_blank" rel="noopener">
          {{ __('site.cta.whatsapp') }}
        </a>
      @endif
      <a class="btn btn-ghost" href="{{ route('contact') }}">{{ __('site.cta.contact') }}</a>
    </div>
  </div>
</section>
