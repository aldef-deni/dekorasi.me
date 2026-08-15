@php
    $siteName = setting('site.name', 'Dekorasi.me');
    $metaTitle = trim($__env->yieldContent('meta-title')) ?: ($siteName . ' — ' . setting_t('site.tagline', __('site.hero.default_eyebrow')));
    $metaDesc  = trim($__env->yieldContent('meta-description')) ?: setting_t('site.description', __('site.footer.default_description'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />

  <title>{{ $metaTitle }}</title>
  <meta name="description" content="{{ $metaDesc }}" />
  @if (setting('seo.keywords'))
    <meta name="keywords" content="{{ setting('seo.keywords') }}" />
  @endif
  <meta name="theme-color" content="#fbfaf7" />
  <link rel="canonical" href="{{ url()->current() }}" />

  <link rel="icon" type="image/png" href="{{ upload_url(setting('site.favicon'), asset('img/brand/logo.png')) }}" />

  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="{{ $siteName }}" />
  <meta property="og:title" content="{{ $metaTitle }}" />
  <meta property="og:description" content="{{ $metaDesc }}" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:image" content="{{ absolute_url(upload_url(setting('seo.og_image'), asset('img/brand/logo.png'))) }}" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('css/site.css') }}?v=14" />
  @stack('styles')
</head>

<body>
  @include('site.partials.header')

  <main>
    @yield('content')
  </main>

  @include('site.partials.footer')

  @if (setting('contact.whatsapp'))
    <a class="wa-float" href="{{ whatsapp_url(setting('contact.whatsapp'), __('site.cta.wa_message', ['site' => $siteName])) }}"
       target="_blank" rel="noopener" aria-label="{{ __('site.common.contact_wa') }}">
      <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.87 9.87 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2zm5.43 12.38c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.64.08-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.64-2.05-.17-.3-.02-.46.13-.6.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.6-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.01-1.04 2.47s1.06 2.86 1.21 3.06c.15.2 2.1 3.2 5.08 4.49.71.3 1.26.49 1.69.63.71.22 1.36.19 1.87.12.57-.09 1.75-.71 2-1.4.25-.69.25-1.28.17-1.4-.07-.13-.27-.2-.57-.35z"/></svg>
    </a>
  @endif

  <script>
    // Header berubah latar setelah di-scroll.
    (function () {
      const header = document.querySelector('.site-header');
      const onScroll = function () {
        header.classList.toggle('scrolled', window.scrollY > 40);
      };
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
    })();

    // Menu mobile.
    (function () {
      const LABEL_BUKA  = @json(__('site.nav.open_menu'));
      const LABEL_TUTUP = @json(__('site.nav.close_menu'));

      const toggle = document.querySelector('.nav-toggle');
      const nav = document.querySelector('.nav');
      const backdrop = document.querySelector('.nav-backdrop');
      if (!toggle || !nav || !backdrop) return;

      const setOpen = function (open) {
        nav.classList.toggle('open', open);
        backdrop.classList.toggle('open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? LABEL_TUTUP : LABEL_BUKA);
        document.body.style.overflow = open ? 'hidden' : '';
      };

      toggle.addEventListener('click', function (event) {
        event.preventDefault();
        setOpen(!nav.classList.contains('open'));
      });

      backdrop.addEventListener('click', () => setOpen(false));

      const closeBtn = nav.querySelector('.nav-close');
      if (closeBtn) closeBtn.addEventListener('click', () => setOpen(false));

      nav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setOpen(false)));
      document.addEventListener('keydown', e => e.key === 'Escape' && setOpen(false));

      // Kembali ke tampilan desktop: pastikan drawer & kunci scroll dilepas.
      window.matchMedia('(min-width: 821px)').addEventListener('change', e => e.matches && setOpen(false));
    })();

    // Animasi muncul saat elemen masuk viewport.
    (function () {
      const items = document.querySelectorAll('.reveal');
      if (!items.length) return;

      if (!('IntersectionObserver' in window)) {
        items.forEach(el => el.classList.add('visible'));
        return;
      }

      const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

      items.forEach(function (el, index) {
        el.style.transitionDelay = Math.min(index % 6, 5) * 70 + 'ms';
        observer.observe(el);
      });
    })();
  </script>

  @stack('scripts')
</body>
</html>
