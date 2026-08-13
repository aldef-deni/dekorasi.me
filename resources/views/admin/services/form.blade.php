@extends('layouts.admin')

@php
    $isEdit = $service->exists;

    // Pilihan ikon Tabler yang relevan untuk paket desain interior.
    $iconOptions = [
        'tabler-pencil'         => 'Desain / Sketsa (Silver)',
        'tabler-armchair'       => 'Furnitur Custom (Gold)',
        'tabler-building-arch'  => 'Turn-Key / Konstruksi (Platinum)',
        'tabler-cube-3d-sphere' => 'Visualisasi 3D',
        'tabler-ruler-2'        => 'Perencanaan & Ukur',
        'tabler-sofa'           => 'Sofa / Ruang Keluarga',
        'tabler-home'           => 'Rumah',
        'tabler-building-store' => 'Komersial / Toko',
        'tabler-bulb'           => 'Pencahayaan',
        'tabler-paint'          => 'Finishing & Warna',
        'tabler-tools'          => 'Konstruksi',
        'tabler-messages'       => 'Konsultasi',
    ];
@endphp

@section('title', $isEdit ? 'Ubah Paket' : 'Tambah Paket')
@section('page-title', $isEdit ? 'Ubah Paket Layanan' : 'Tambah Paket Layanan')
@section('page-subtitle', $isEdit ? $service->title : 'Mis. Paket Silver, Gold, atau Platinum')

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
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Identitas Paket</h5></div>
          <div class="card-body">

            <div class="row">
              <div class="col-md-6 mb-4">
                <label class="form-label" for="title">Nama Paket <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror"
                       id="title" name="title" value="{{ old('title', $service->title) }}" required
                       placeholder="Paket Silver" />
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="subtitle">Keterangan Paket</label>
                <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                       id="subtitle" name="subtitle" value="{{ old('subtitle', $service->subtitle) }}"
                       placeholder="Design Only" />
                @error('subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Tampil kecil di bawah nama paket.</div>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label" for="slug">Slug URL</label>
              <input type="text" class="form-control @error('slug') is-invalid @enderror"
                     id="slug" name="slug" value="{{ old('slug', $service->slug) }}"
                     placeholder="otomatis dari nama paket" />
              @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-0">
              <label class="form-label" for="excerpt">Ringkasan</label>
              <textarea class="form-control @error('excerpt') is-invalid @enderror"
                        id="excerpt" name="excerpt" rows="2"
                        placeholder="Satu kalimat singkat yang muncul di bawah nama paket.">{{ old('excerpt', $service->excerpt) }}</textarea>
              @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-1">Isi Paket</h5>
            <p class="mb-0 text-body-secondary small">
              Tulis <strong>satu poin per baris</strong>. Tanda hubung di awal baris otomatis dibuang.
            </p>
          </div>
          <div class="card-body">
            <textarea class="form-control font-monospace @error('features') is-invalid @enderror"
                      id="features" name="features" rows="9"
                      placeholder="Konsultasi konsep awal.&#10;Tata letak ruangan (Layout 2D Layout Plan).&#10;Visualisasi desain (3D Rendering hingga 3 view).&#10;Gambar kerja teknis (Production Drawing).">{{ old('features', $service->features) }}</textarea>
            @error('features') <div class="invalid-feedback">{{ $message }}</div> @enderror

            @if ($isEdit && $service->featureList()->isNotEmpty())
              <div class="mt-3">
                <span class="badge bg-label-info">{{ $service->featureList()->count() }} poin tersimpan</span>
              </div>
            @endif
          </div>
        </div>

        <div class="card">
          <div class="card-header"><h5 class="mb-0">Deskripsi Lengkap</h5></div>
          <div class="card-body">
            <textarea class="form-control @error('description') is-invalid @enderror"
                      id="description" name="description" rows="8"
                      placeholder="Penjelasan panjang yang tampil di halaman detail paket.">{{ old('description', $service->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Pisahkan antar paragraf dengan baris kosong. Opsional.</div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Harga</h5></div>
          <div class="card-body">
            <input type="text" class="form-control @error('price') is-invalid @enderror"
                   name="price" value="{{ old('price', $service->price) }}"
                   placeholder="Mulai Rp 350.000 / m²" />
            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">
              Teks bebas. Kosongkan bila tidak ingin menampilkan harga.
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Ikon</h5></div>
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <span class="stat-icon">
                <i class="icon-base ti {{ old('icon', $service->icon) ?: 'tabler-pencil' }}" id="icon-preview"></i>
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
            <div class="form-text">Opsional. Tampil di halaman detail paket.</div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Publikasi</h5></div>
          <div class="card-body">
            <div class="mb-4">
              <label class="form-label" for="sort_order">Urutan Tampil</label>
              <input type="number" class="form-control" id="sort_order" name="sort_order" min="0"
                     value="{{ old('sort_order', $service->sort_order ?? 0) }}" />
              <div class="form-text">Angka lebih kecil tampil lebih dulu.</div>
            </div>

            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                     {{ old('is_active', $service->exists ? $service->is_active : true) ? 'checked' : '' }} />
              <label class="form-check-label" for="is_active">Tampilkan di website</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                     {{ old('is_featured', $service->is_featured) ? 'checked' : '' }} />
              <label class="form-check-label" for="is_featured">Tandai sebagai paket unggulan</label>
              <div class="form-text">Diberi sorotan &amp; label &ldquo;Paling Diminati&rdquo;.</div>
            </div>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-1"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Paket' }}
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
