@extends('layouts.admin')

@section('title', 'Tentang Kami')
@section('page-title', 'Halaman Tentang Kami')
@section('page-subtitle', 'Profil perusahaan, visi & misi, serta angka pencapaian')

@section('content')
  <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Profil Perusahaan</h5></div>
          <div class="card-body">

            <div class="mb-4">
              <label class="form-label" for="about_heading">Judul Halaman <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('about_heading') is-invalid @enderror"
                     id="about_heading" name="about_heading" required
                     value="{{ old('about_heading', setting('about.heading', 'Tentang Dekorasi.me')) }}" />
              @error('about_heading') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="about_subtitle">Subjudul</label>
              <input type="text" class="form-control" id="about_subtitle" name="about_subtitle"
                     value="{{ old('about_subtitle', setting('about.subtitle')) }}"
                     placeholder="Studio desain interior yang mengubah ruang menjadi pengalaman" />
            </div>

            <div class="mb-2">
              <label class="form-label" for="about_body">Isi / Cerita Perusahaan</label>
              <textarea class="form-control @error('about_body') is-invalid @enderror"
                        id="about_body" name="about_body" rows="12"
                        placeholder="Ceritakan sejarah, pendekatan desain, dan nilai yang Anda pegang.">{{ old('about_body', setting('about.body')) }}</textarea>
              @error('about_body') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="form-text">Pisahkan antar paragraf dengan baris kosong.</div>
            </div>

          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Visi &amp; Misi</h5></div>
          <div class="card-body">
            <div class="mb-4">
              <label class="form-label" for="about_vision">Visi</label>
              <textarea class="form-control" id="about_vision" name="about_vision" rows="3"
                        placeholder="Menjadi studio desain interior terpercaya di Indonesia…">{{ old('about_vision', setting('about.vision')) }}</textarea>
            </div>
            <div class="mb-0">
              <label class="form-label" for="about_mission">Misi</label>
              <textarea class="form-control" id="about_mission" name="about_mission" rows="7"
                        placeholder="Kualitas Prima : Menyediakan produk furnitur dengan standar material terbaik.&#10;Inovasi Desain : Terus beradaptasi dengan tren desain global.">{{ old('about_mission', setting('about.mission')) }}</textarea>
              <div class="form-text">
                Satu poin per baris. Gunakan format <code>Judul : Penjelasan</code> —
                bagian sebelum titik dua menjadi judul poin, sisanya menjadi penjelasan.
                Baris tanpa titik dua tetap tampil, hanya tanpa penjelasan.
              </div>

              @php $pratinjauMisi = parse_poin(setting('about.mission')); @endphp

              @if ($pratinjauMisi->isNotEmpty())
                <div class="mt-3 p-3 rounded" style="background:var(--bs-body-bg);border:1px solid var(--bs-border-color)">
                  <p class="mb-2 small text-body-secondary">Pratinjau tampilan di website:</p>
                  <ol class="mb-0 ps-3 small">
                    @foreach ($pratinjauMisi as $poin)
                      <li class="mb-1">
                        <strong>{{ $poin['label'] }}</strong>
                        @if ($poin['teks'])
                          <span class="text-body-secondary">— {{ $poin['teks'] }}</span>
                        @endif
                      </li>
                    @endforeach
                  </ol>
                </div>
              @endif
            </div>
          </div>
        </div>


        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-1 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-language text-primary"></i> Versi Bahasa Inggris
            </h5>
            <p class="mb-0 text-body-secondary small">
              Kolom yang dikosongkan otomatis memakai teks bahasa Indonesia.
            </p>
          </div>
          <div class="card-body">
            <div class="mb-4">
              <label class="form-label" for="about_heading_en">Page Title (English)</label>
              <input type="text" class="form-control" id="about_heading_en" name="about_heading_en"
                     value="{{ old('about_heading_en', setting('about.heading_en')) }}"
                     placeholder="Small Details, Great Impact" />
            </div>
            <div class="mb-4">
              <label class="form-label" for="about_subtitle_en">Subtitle (English)</label>
              <input type="text" class="form-control" id="about_subtitle_en" name="about_subtitle_en"
                     value="{{ old('about_subtitle_en', setting('about.subtitle_en')) }}" />
            </div>
            <div class="mb-4">
              <label class="form-label" for="about_body_en">Company Story (English)</label>
              <textarea class="form-control" id="about_body_en" name="about_body_en" rows="8">{{ old('about_body_en', setting('about.body_en')) }}</textarea>
            </div>
            <div class="mb-4">
              <label class="form-label" for="about_vision_en">Vision (English)</label>
              <textarea class="form-control" id="about_vision_en" name="about_vision_en" rows="3">{{ old('about_vision_en', setting('about.vision_en')) }}</textarea>
            </div>
            <div class="mb-0">
              <label class="form-label" for="about_mission_en">Mission (English)</label>
              <textarea class="form-control" id="about_mission_en" name="about_mission_en" rows="6"
                        placeholder="Prime Quality : Providing furniture and interior renovation…">{{ old('about_mission_en', setting('about.mission_en')) }}</textarea>
              <div class="form-text">Format <code>Title : Description</code>, satu poin per baris.</div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h5 class="mb-1">Angka Pencapaian</h5>
            <p class="mb-0 text-body-secondary small">Empat angka yang tampil sebagai penguat kredibilitas.</p>
          </div>
          <div class="card-body">
            <div class="row">
              @for ($i = 1; $i <= 4; $i++)
                <div class="col-md-6 mb-4">
                  <label class="form-label">Statistik {{ $i }}</label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="about_stat{{ $i }}_value" style="max-width:110px"
                           value="{{ old("about_stat{$i}_value", setting("about.stat{$i}_value")) }}"
                           placeholder="{{ ['120+', '8', '95%', '15'][$i - 1] }}" />
                    <input type="text" class="form-control" name="about_stat{{ $i }}_label"
                           value="{{ old("about_stat{$i}_label", setting("about.stat{$i}_label")) }}"
                           placeholder="{{ ['Proyek Selesai', 'Tahun Pengalaman', 'Klien Puas', 'Tim Desainer'][$i - 1] }}" />
                  </div>

                  <input type="text" class="form-control mt-2" name="about_stat{{ $i }}_label_en"
                         value="{{ old("about_stat{$i}_label_en", setting("about.stat{$i}_label_en")) }}"
                         placeholder="{{ ['Projects Completed', 'Years of Experience', 'Clients Recommend Us', 'Professional Team'][$i - 1] }} (English)" />
                </div>
              @endfor
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Gambar Utama</h5></div>
          <div class="card-body">
            <img id="about-preview" src="{{ upload_url(setting('about.image')) }}"
                 class="image-preview mb-3 {{ setting('about.image') ? '' : 'd-none' }}" alt="Pratinjau" />
            <input type="file" class="form-control @error('about_image') is-invalid @enderror"
                   name="about_image" accept="image/*" data-preview="#about-preview" />
            @error('about_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Foto studio, tim, atau proyek terbaik Anda.</div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Gambar Pendamping</h5></div>
          <div class="card-body">
            <img id="about-preview-2" src="{{ upload_url(setting('about.image_secondary')) }}"
                 class="image-preview mb-3 {{ setting('about.image_secondary') ? '' : 'd-none' }}" alt="Pratinjau" />
            <input type="file" class="form-control" name="about_image_secondary" accept="image/*"
                   data-preview="#about-preview-2" />
            <div class="form-text">Opsional. Ditampilkan bertumpuk dengan gambar utama.</div>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Perubahan
          </button>
          <a href="{{ route('about') }}" target="_blank" rel="noopener" class="btn btn-label-secondary">
            <i class="icon-base ti tabler-external-link me-1"></i> Lihat Halaman
          </a>
        </div>
      </div>
    </div>
  </form>
@endsection
