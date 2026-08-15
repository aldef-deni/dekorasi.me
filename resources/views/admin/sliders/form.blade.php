@extends('layouts.admin')

@php $isEdit = $slider->exists; @endphp

@section('title', $isEdit ? 'Ubah Slide' : 'Tambah Slide')
@section('page-title', $isEdit ? 'Ubah Slide' : 'Tambah Slide')
@section('page-subtitle', 'Gambar sebaiknya berukuran lebar (mis. 1920 × 1080 px) agar tajam di layar besar')

@section('page-actions')
  <a href="{{ route('admin.sliders.index') }}" class="btn btn-label-secondary">
    <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
  </a>
@endsection

@section('content')
  <form method="POST"
        action="{{ $isEdit ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}"
        enctype="multipart/form-data">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header"><h5 class="mb-0">Konten Slide</h5></div>
          <div class="card-body">

            <div class="mb-4">
              <label class="form-label" for="title">Judul <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('title') is-invalid @enderror"
                     id="title" name="title" value="{{ old('title', $slider->title) }}" required
                     placeholder="Wujudkan Ruang Impian Anda" />
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="subtitle">Subjudul</label>
              <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                     id="subtitle" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}"
                     placeholder="Desain Interior Premium" />
              @error('subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="description">Deskripsi Singkat</label>
              <textarea class="form-control @error('description') is-invalid @enderror"
                        id="description" name="description" rows="3"
                        placeholder="Satu sampai dua kalimat yang menjelaskan keunggulan Anda.">{{ old('description', $slider->description) }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <label class="form-label" for="cta_label">Label Tombol</label>
                <input type="text" class="form-control" id="cta_label" name="cta_label"
                       value="{{ old('cta_label', $slider->cta_label) }}" placeholder="Lihat Portofolio" />
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label" for="cta_url">Tautan Tombol</label>
                <input type="text" class="form-control" id="cta_url" name="cta_url"
                       value="{{ old('cta_url', $slider->cta_url) }}" placeholder="/proyek" />
                <div class="form-text">Boleh path internal (<code>/proyek</code>) atau URL lengkap.</div>
              </div>
            </div>

          </div>
        </div>

        @include('admin.partials.translation-card', [
            'model'  => $slider,
            'fields' => [
                'title'       => ['Title', 'text', 'Spaces That Tell Your Story'],
                'subtitle'    => ['Subtitle', 'text', 'Premium Interior Design'],
                'description' => ['Description', 'textarea', 'One or two sentences describing your strength.'],
                'cta_label'   => ['Button Label', 'text', 'View Portfolio'],
            ],
        ])
      </div>

      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Gambar Latar</h5></div>
          <div class="card-body">
            <img id="slider-preview"
                 src="{{ upload_url($slider->image) }}"
                 class="image-preview mb-3 {{ $slider->image ? '' : 'd-none' }}" alt="Pratinjau" />

            <input type="file" class="form-control @error('image') is-invalid @enderror"
                   name="image" accept="image/*" data-preview="#slider-preview"
                   {{ $isEdit ? '' : 'required' }} />
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">JPG, PNG, atau WEBP. Maksimal 4 MB.</div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Publikasi</h5></div>
          <div class="card-body">
            <div class="mb-4">
              <label class="form-label" for="sort_order">Urutan Tampil</label>
              <input type="number" class="form-control" id="sort_order" name="sort_order" min="0"
                     value="{{ old('sort_order', $slider->sort_order ?? 0) }}" />
              <div class="form-text">Angka lebih kecil tampil lebih dulu.</div>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                     {{ old('is_active', $slider->exists ? $slider->is_active : true) ? 'checked' : '' }} />
              <label class="form-check-label" for="is_active">Tampilkan di website</label>
            </div>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-1"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Slide' }}
          </button>
          <a href="{{ route('admin.sliders.index') }}" class="btn btn-label-secondary">Batal</a>
        </div>
      </div>
    </div>
  </form>
@endsection
