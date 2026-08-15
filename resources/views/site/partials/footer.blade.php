@php
    $socials = array_filter([
        'instagram' => setting('social.instagram'),
        'facebook'  => setting('social.facebook'),
        'tiktok'    => setting('social.tiktok'),
        'youtube'   => setting('social.youtube'),
        'linkedin'  => setting('social.linkedin'),
    ]);

    $footerServices = \App\Models\Service::active()->ordered()->take(5)->get();
@endphp

<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">

      <div>
        <a href="{{ route('home') }}" class="brand" style="margin-bottom:18px">
          <img src="{{ upload_url(setting('site.logo'), asset('img/brand/mark.png')) }}"
               alt="{{ setting('site.name', 'Dekorasi.me') }}" />
          <span>{{ setting('site.name', 'Dekorasi.me') }}</span>
        </a>
        <p>{{ setting_t('site.description', __('site.footer.default_description')) }}</p>

        @if ($socials)
          <div class="socials">
            @foreach ($socials as $network => $url)
              <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}">
                @switch($network)
                  @case('instagram')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                    @break
                  @case('facebook')
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H8v3h2v7h3v-7h3l1-3h-4v-2c0-.6.4-1 1-1z"/></svg>
                    @break
                  @case('tiktok')
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 3c.4 2.2 1.8 3.7 4 4v3c-1.5 0-2.9-.4-4-1.2V15a6 6 0 1 1-6-6c.3 0 .7 0 1 .1v3.2A2.8 2.8 0 1 0 13 15V3h3z"/></svg>
                    @break
                  @case('youtube')
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12s0-3.2-.4-4.7c-.2-.8-.9-1.5-1.7-1.7C18.4 5.2 12 5.2 12 5.2s-6.4 0-7.9.4c-.8.2-1.5.9-1.7 1.7C2 8.8 2 12 2 12s0 3.2.4 4.7c.2.8.9 1.5 1.7 1.7 1.5.4 7.9.4 7.9.4s6.4 0 7.9-.4c.8-.2 1.5-.9 1.7-1.7.4-1.5.4-4.7.4-4.7zM10 15V9l5.2 3L10 15z"/></svg>
                    @break
                  @default
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM3 9h4v12H3V9zm7 0h3.8v1.7h.05c.53-1 1.83-2.05 3.75-2.05C21.4 8.65 22 10.9 22 13.7V21h-4v-6.5c0-1.55-.03-3.55-2.15-3.55-2.15 0-2.48 1.7-2.48 3.45V21h-4V9z"/></svg>
                @endswitch
              </a>
            @endforeach
          </div>
        @endif
      </div>

      <div>
        <h4>{{ __('site.footer.navigation') }}</h4>
        <ul>
          <li><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a></li>
          <li><a href="{{ route('about') }}">{{ __('site.nav.about') }}</a></li>
          <li><a href="{{ route('services.index') }}">{{ __('site.nav.services') }}</a></li>
          <li><a href="{{ route('projects.index') }}">{{ __('site.nav.projects') }}</a></li>
          <li><a href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a></li>
        </ul>
      </div>

      <div>
        <h4>{{ __('site.footer.services') }}</h4>
        <ul>
          @forelse ($footerServices as $service)
            <li><a href="{{ route('services.show', $service) }}">{{ $service->t('title') }}</a></li>
          @empty
            <li>{{ __('site.footer.package_silver') }}</li>
            <li>{{ __('site.footer.package_gold') }}</li>
            <li>{{ __('site.footer.package_platinum') }}</li>
          @endforelse
        </ul>
      </div>

      <div>
        <h4>{{ __('site.footer.contact') }}</h4>
        <ul>
          @if (setting('contact.address'))
            <li>{{ setting('contact.address') }}</li>
          @endif
          @if (setting('contact.phone'))
            <li><a href="tel:{{ preg_replace('/\s+/', '', setting('contact.phone')) }}">{{ setting('contact.phone') }}</a></li>
          @endif
          @if (setting('contact.email'))
            <li><a href="mailto:{{ setting('contact.email') }}">{{ setting('contact.email') }}</a></li>
          @endif
          @if (setting('contact.hours'))
            <li>{{ setting('contact.hours') }}</li>
          @endif
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <span>&copy; {{ date('Y') }} {{ setting('site.name', 'Dekorasi.me') }}. {{ __('site.footer.rights') }}</span>
      <span>{{ setting_t('site.tagline', __('site.hero.default_eyebrow')) }}</span>
    </div>
  </div>
</footer>
