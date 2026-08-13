@extends('layouts.admin')

@php
    $isEdit = $service->exists;

    // Pilihan ikon Tabler yang relevan untuk bisnis desain interior.
    $iconOptions = [
        'tabler-sofa'           => 'Sofa / Ruang Keluarga',
        'tabler-home'           => 'Rumah',
        'tabler-building-store' => 'Komersial / Toko',
        'tabler-building-arch'  => 'Arsitektur',
        'tabler-ruler-2'        => 'Perencanaan & Ukur',
        'tabler-pencil'         => 'Desain / Sketsa',
        'tabler-cube-3d-sphere' => 'Visualisasi 3D',
        'tabler-armchair'       => 'Furnitur Custom',
        'tabler-bulb'           => 'Pencahayaan',
        'tabler-paint'          => 'Finishing & Warna',
        'tabler-tools'          => 'Konstruksi',
        'tabler-messages'       => 'Konsultasi',
    ];
@endphp

@section('title', $isEdit ? 'Ubah Layanan' : 'Tambah Layanan')
@section('page-title', $isEdit ? 'Ubah Layanan' : 'Tambah Layanan')

@section('page-actions')
  <a href="{{ route('admin.services.index') }}" class="btn btn-label-secondary">
    <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
  </a>
@endsection

@section('content')
  <form method="POST"
        action="{{ $isEdit ? route('admin.services.update', $service) : route('admin.services.store') }}"
        enctype="multipart/form-data">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header"><h5 class="mb-0">Detail Layanan</h5></div>
          <div class="card-body">

            <div class="mb-4">
              <label class="form-label" for="title">Nama Layanan <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('title') is-invalid @enderror"
                     id="title" name="title" value="{{ old('title', $service->title) }}" required
                     placeholder="Desain Interior Residensial" />
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="slug">Slug URL</label>
              <input type="text" class="form-control @error('slug') is-invalid @enderror"
                     id="slug" name="slug" value="{{ old('slug', $service->slug) }}"
                     placeholder="otomatis dari nama layanan" />
              @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="form-text">Kosongkan untuk dibuat otomatis dari nama layanan.</div>
            </div>

            <div class="mb-4">
              <label class="form-label" for="excerpt">Ringkasan</label>
              <textarea class="form-control @error('excerpt') is-invalid @enderror"
                        id="excerpt" name="excerpt" rows="2"
                        placeholder="Satu kalimat singkat yang muncul di kartu layanan.">{{ old('excerpt', $service->excerpt) }}</textarea>
              @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-2">
              <label class="form-label" for="description">Deskripsi Lengkap</label>
              <textarea class="form-control @error('description') is-invalid @enderror"
                        id="description" name="description" rows="10"
                        placeholder="Jelaskan cakupan pekerjaan, proses, dan hasil yang klien dapatkan.">{{ old('description', $service->description) }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="form-text">Pisahkan antar paragraf dengan baris kosong.</div>
            </div>

          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Ikon</h5></div>
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <span class="stat-icon" id="icon-preview-wrap">
                <i class="icon-base ti {{ old('icon', $service->icon) ?: 'tabler-sofa' }}" id="icon-preview"></i>
              </span>
              <select class="form-select" name="icon" id="icon">
                @foreach ($iconOptions as $value => $label)
                  <option value="{{ $value }}" {{ old('icon', $service->icon) === $value ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Gambar Pendukung</h5></div>
          <div class="card-body">
            <img id="service-preview" src="{{ upload_url($service->image) }}"
                 class="image-preview mb-3 {{ $service->image ? '' : 'd-none' }}" alt="Pratinjau" />
            <input type="file" class="form-control @error('image') is-invalid @enderror"
                   name="image" accept="image/*" data-preview="#service-preview" />
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Opsional. Tampil di halaman detail layanan.</div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Publikasi</h5></div>
          <div class="card-body">
            <div class="mb-4">
              <label class="form-label" for="sort_order">Urutan Tampil</label>
              <input type="number" class="form-control" id="sort_order" name="sort_order" min="0"
                     value="{{ old('sort_order', $service->sort_order ?? 0) }}" />
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                     {{ old('is_active', $service->exists ? $service->is_active : true) ? 'checked' : '' }} />
              <label class="form-check-label" for="is_active">Tampilkan di website</label>
            </div>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-1"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Layanan' }}
          </button>
          <a href="{{ route('admin.services.index') }}" class="btn btn-label-secondary">Batal</a>
        </div>
      </div>
    </div>
  </form>
@endsection

@push('scripts')
<script>
  // Pratinjau ikon mengikuti pilihan dropdown.
  document.getElementById('icon').addEventListener('change', function () {
    document.getElementById('icon-preview').className = 'icon-base ti ' + this.value;
  });
</script>
@endpush
