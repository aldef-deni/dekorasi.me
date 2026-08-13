@php $siteName = setting('site.name', 'Dekorasi.me'); @endphp

<section class="cta">
  <div class="wrap reveal">
    <span class="eyebrow" style="justify-content:center">Mulai Proyek Anda</span>
    <h2>Punya Ruang yang Ingin <span class="gold-text">Diubah?</span></h2>
    <p class="lead">
      Ceritakan kebutuhan Anda — kami bantu dari konsep sampai ruangnya benar-benar jadi.
      Konsultasi awal tidak dipungut biaya.
    </p>

    <div style="display:flex;flex-wrap:wrap;gap:14px;justify-content:center">
      @if (setting('contact.whatsapp'))
        <a class="btn btn-gold"
           href="{{ whatsapp_url(setting('contact.whatsapp'), 'Halo ' . $siteName . ', saya ingin konsultasi desain interior.') }}"
           target="_blank" rel="noopener">
          Konsultasi via WhatsApp
        </a>
      @endif
      <a class="btn btn-ghost" href="{{ route('contact') }}">Lihat Kontak Lengkap</a>
    </div>
  </div>
</section>
