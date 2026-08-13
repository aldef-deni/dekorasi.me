@extends('layouts.admin')

@section('title', 'Pengaturan Situs')
@section('page-title', 'Pengaturan Situs')
@section('page-subtitle', 'Identitas, kontak, sosial media, dan SEO')

@section('content')
  <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-4">
      <div class="col-lg-8">

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Identitas Situs</h5></div>
          <div class="card-body">
            <div class="mb-4">
              <label class="form-label" for="site_name">Nama Situs <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('site_name') is-invalid @enderror"
                     id="site_name" name="site_name" required
                     value="{{ old('site_name', setting('site.name', 'Dekorasi.me')) }}" />
              @error('site_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="site_tagline">Tagline</label>
              <input type="text" class="form-control" id="site_tagline" name="site_tagline"
                     value="{{ old('site_tagline', setting('site.tagline')) }}"
                     placeholder="Desain Interior Premium" />
            </div>

            <div class="mb-0">
              <label class="form-label" for="site_description">Deskripsi Singkat</label>
              <textarea class="form-control" id="site_description" name="site_description" rows="3"
                        placeholder="Dipakai sebagai meta description dan teks footer.">{{ old('site_description', setting('site.description')) }}</textarea>
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Kontak</h5></div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-4">
                <label class="form-label" for="contact_phone">Telepon</label>
                <input type="text" class="form-control" id="contact_phone" name="contact_phone"
                       value="{{ old('contact_phone', setting('contact.phone')) }}" placeholder="021-1234567" />
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label" for="contact_whatsapp">Nomor WhatsApp</label>
                <input type="text" class="form-control" id="contact_whatsapp" name="contact_whatsapp"
                       value="{{ old('contact_whatsapp', setting('contact.whatsapp')) }}" placeholder="081234567890" />
                <div class="form-text">Boleh format 08… atau 628… — otomatis dikonversi ke tautan wa.me.</div>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label" for="contact_email">Email</label>
                <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                       id="contact_email" name="contact_email"
                       value="{{ old('contact_email', setting('contact.email')) }}" placeholder="halo@dekorasi.me" />
                @error('contact_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label" for="contact_hours">Jam Operasional</label>
                <input type="text" class="form-control" id="contact_hours" name="contact_hours"
                       value="{{ old('contact_hours', setting('contact.hours')) }}"
                       placeholder="Senin – Sabtu, 09.00 – 17.00 WIB" />
              </div>
              <div class="col-12 mb-4">
                <label class="form-label" for="contact_address">Alamat</label>
                <textarea class="form-control" id="contact_address" name="contact_address" rows="2"
                          placeholder="Jl. Contoh No. 12, Jakarta Selatan">{{ old('contact_address', setting('contact.address')) }}</textarea>
              </div>
              <div class="col-12 mb-0">
                <label class="form-label" for="contact_maps_embed">Kode Embed Google Maps</label>
                <textarea class="form-control" id="contact_maps_embed" name="contact_maps_embed" rows="3"
                          placeholder="https://www.google.com/maps/embed?pb=…">{{ old('contact_maps_embed', setting('contact.maps_embed')) }}</textarea>
                <div class="form-text">
                  Tempel <strong>URL saja</strong> dari Google Maps &rsaquo; Bagikan &rsaquo; Sematkan peta
                  (bagian <code>src="…"</code>), bukan seluruh tag iframe.
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Sosial Media</h5></div>
          <div class="card-body">
            <div class="row">
              @php
                  $socials = [
                      'social_instagram' => ['Instagram', 'tabler-brand-instagram', 'https://instagram.com/dekorasi.me'],
                      'social_facebook'  => ['Facebook',  'tabler-brand-facebook',  'https://facebook.com/dekorasi.me'],
                      'social_tiktok'    => ['TikTok',    'tabler-brand-tiktok',    'https://tiktok.com/@dekorasi.me'],
                      'social_youtube'   => ['YouTube',   'tabler-brand-youtube',   'https://youtube.com/@dekorasi.me'],
                      'social_linkedin'  => ['LinkedIn',  'tabler-brand-linkedin',  'https://linkedin.com/company/dekorasi-me'],
                  ];
              @endphp

              @foreach ($socials as $name => [$label, $icon, $placeholder])
                <div class="col-md-6 mb-4">
                  <label class="form-label" for="{{ $name }}">{{ $label }}</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="icon-base ti {{ $icon }}"></i></span>
                    <input type="text" class="form-control" id="{{ $name }}" name="{{ $name }}"
                           value="{{ old($name, setting(str_replace('_', '.', $name))) }}"
                           placeholder="{{ $placeholder }}" />
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-1">Banner Halaman</h5>
            <p class="mb-0 text-body-secondary small">
              Gambar besar di kepala tiap halaman. Ukuran ideal
              <strong>1920 &times; 800 px</strong>; bagian tengah gambar yang paling terlihat.
              Bila dikosongkan, banner diambil <strong>otomatis</strong> dari gambar slider
              dan sampul proyek yang sudah ada &mdash; tiap halaman mendapat gambar berbeda.
            </p>
          </div>
          <div class="card-body">
            <div class="row">
              @php
                  $banners = [
                      'banner_about'    => ['Tentang Kami',  'banner.about',    'about'],
                      'banner_services' => ['Paket Layanan', 'banner.services', 'services.index'],
                      'banner_projects' => ['Portofolio',    'banner.projects', 'projects.index'],
                      'banner_contact'  => ['Kontak',        'banner.contact',  'contact'],
                  ];
              @endphp

              @foreach ($banners as $name => [$label, $key, $routeName])
                <div class="col-md-6 mb-4">
                  <label class="form-label d-flex align-items-center justify-content-between" for="{{ $name }}">
                    <span>{{ $label }}</span>
                    <a href="{{ route($routeName) }}" target="_blank" rel="noopener"
                       class="text-body-secondary small text-decoration-none" title="Lihat halaman">
                      <i class="icon-base ti tabler-external-link icon-16px"></i>
                    </a>
                  </label>

                  @php $pageKey = Str::after($key, 'banner.'); @endphp

                  <img id="preview-{{ $name }}" src="{{ banner_url($pageKey) }}"
                       class="image-preview mb-2" style="max-width:100%;height:110px;object-fit:cover"
                       alt="Pratinjau banner {{ $label }}" />

                  <input type="file" class="form-control @error($name) is-invalid @enderror"
                         id="{{ $name }}" name="{{ $name }}" accept="image/*"
                         data-preview="#preview-{{ $name }}" />
                  @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror

                  @unless (setting($key))
                    <div class="form-text">
                      {{ \App\Support\Banner::isAutomatic($pageKey)
                          ? 'Diambil otomatis dari gambar yang sudah ada.'
                          : 'Memakai gambar bawaan — belum ada slider/proyek bergambar.' }}
                    </div>
                  @endunless
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><h5 class="mb-0">SEO</h5></div>
          <div class="card-body">
            <div class="mb-4">
              <label class="form-label" for="seo_title">Judul SEO Beranda</label>
              <input type="text" class="form-control" id="seo_title" name="seo_title"
                     value="{{ old('seo_title', setting('seo.title')) }}"
                     placeholder="Jasa Desain Interior Premium — Dekorasi.me" />
            </div>
            <div class="mb-0">
              <label class="form-label" for="seo_keywords">Kata Kunci</label>
              <input type="text" class="form-control" id="seo_keywords" name="seo_keywords"
                     value="{{ old('seo_keywords', setting('seo.keywords')) }}"
                     placeholder="desain interior, interior rumah, jasa interior jakarta" />
              <div class="form-text">Pisahkan dengan koma.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        @php
            $mediaFields = [
                'site_logo'      => ['Logo (latar terang)', 'site.logo',    'Tampil di menu atas halaman depan.'],
                'site_logo_dark' => ['Logo (latar gelap)',  'site.logo_dark','Opsional, versi terang untuk latar gelap.'],
                'site_favicon'   => ['Favicon',             'site.favicon', 'Ikon tab browser. PNG 64×64 px.'],
                'seo_og_image'   => ['Gambar Share',        'seo.og_image', 'Muncul saat link dibagikan (1200×630 px).'],
            ];
        @endphp

        @foreach ($mediaFields as $name => [$label, $key, $help])
          <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">{{ $label }}</h5></div>
            <div class="card-body">
              <img id="preview-{{ $name }}" src="{{ upload_url(setting($key)) }}"
                   class="image-preview mb-3 {{ setting($key) ? '' : 'd-none' }}" alt="Pratinjau" />
              <input type="file" class="form-control @error($name) is-invalid @enderror"
                     name="{{ $name }}" accept="image/*" data-preview="#preview-{{ $name }}" />
              @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="form-text">{{ $help }}</div>
            </div>
          </div>
        @endforeach

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Pengaturan
          </button>
        </div>
      </div>
    </div>
  </form>
@endsection
