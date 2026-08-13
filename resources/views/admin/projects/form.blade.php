@extends('layouts.admin')

@php $isEdit = $project->exists; @endphp

@section('title', $isEdit ? 'Ubah Proyek' : 'Tambah Proyek')
@section('page-title', $isEdit ? 'Ubah Proyek' : 'Tambah Proyek')
@section('page-subtitle', $isEdit ? $project->title : 'Isi detail proyek, galeri foto bisa ditambahkan setelah disimpan')

@section('page-actions')
  <a href="{{ route('admin.projects.index') }}" class="btn btn-label-secondary">
    <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
  </a>
@endsection

@section('content')
  <form method="POST"
        action="{{ $isEdit ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
        enctype="multipart/form-data">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Informasi Proyek</h5></div>
          <div class="card-body">

            <div class="mb-4">
              <label class="form-label" for="title">Nama Proyek <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('title') is-invalid @enderror"
                     id="title" name="title" value="{{ old('title', $project->title) }}" required
                     placeholder="Rumah Minimalis Modern Bintaro" />
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="slug">Slug URL</label>
              <input type="text" class="form-control @error('slug') is-invalid @enderror"
                     id="slug" name="slug" value="{{ old('slug', $project->slug) }}"
                     placeholder="otomatis dari nama proyek" />
              @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <label class="form-label" for="category">Kategori</label>
                <input type="text" class="form-control" id="category" name="category" list="category-list"
                       value="{{ old('category', $project->category) }}" placeholder="Residensial" />
                <datalist id="category-list">
                  <option value="Residensial"></option>
                  <option value="Komersial"></option>
                  <option value="Kantor"></option>
                  <option value="Apartemen"></option>
                  <option value="Kafe &amp; Restoran"></option>
                  <option value="Retail"></option>
                </datalist>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label" for="client">Klien</label>
                <input type="text" class="form-control" id="client" name="client"
                       value="{{ old('client', $project->client) }}" placeholder="Bpk. Andi" />
              </div>
              <div class="col-md-4 mb-4">
                <label class="form-label" for="location">Lokasi</label>
                <input type="text" class="form-control" id="location" name="location"
                       value="{{ old('location', $project->location) }}" placeholder="Jakarta Selatan" />
              </div>
              <div class="col-md-4 mb-4">
                <label class="form-label" for="area">Luas</label>
                <input type="text" class="form-control" id="area" name="area"
                       value="{{ old('area', $project->area) }}" placeholder="180 m²" />
              </div>
              <div class="col-md-4 mb-4">
                <label class="form-label" for="year">Tahun</label>
                <input type="number" class="form-control @error('year') is-invalid @enderror"
                       id="year" name="year" min="1950" max="{{ date('Y') + 5 }}"
                       value="{{ old('year', $project->year) }}" placeholder="{{ date('Y') }}" />
                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label" for="excerpt">Ringkasan</label>
              <textarea class="form-control @error('excerpt') is-invalid @enderror"
                        id="excerpt" name="excerpt" rows="2"
                        placeholder="Kalimat singkat yang muncul di kartu portofolio.">{{ old('excerpt', $project->excerpt) }}</textarea>
              @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-2">
              <label class="form-label" for="description">Deskripsi Lengkap</label>
              <textarea class="form-control @error('description') is-invalid @enderror"
                        id="description" name="description" rows="10"
                        placeholder="Ceritakan konsep desain, tantangan, material yang dipakai, dan hasil akhirnya.">{{ old('description', $project->description) }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="form-text">Pisahkan antar paragraf dengan baris kosong.</div>
            </div>

          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Gambar Sampul</h5></div>
          <div class="card-body">
            <img id="cover-preview" src="{{ upload_url($project->cover_image) }}"
                 class="image-preview mb-3 {{ $project->cover_image ? '' : 'd-none' }}" alt="Pratinjau" />
            <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                   name="cover_image" accept="image/*" data-preview="#cover-preview" />
            @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Foto utama proyek. Maksimal 6 MB.</div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Publikasi</h5></div>
          <div class="card-body">
            <div class="mb-4">
              <label class="form-label" for="sort_order">Urutan Tampil</label>
              <input type="number" class="form-control" id="sort_order" name="sort_order" min="0"
                     value="{{ old('sort_order', $project->sort_order ?? 0) }}" />
            </div>

            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                     {{ old('is_active', $project->exists ? $project->is_active : true) ? 'checked' : '' }} />
              <label class="form-check-label" for="is_active">Tampilkan di website</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                     {{ old('is_featured', $project->is_featured) ? 'checked' : '' }} />
              <label class="form-check-label" for="is_featured">Tampilkan di beranda (unggulan)</label>
            </div>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-1"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan & Lanjut ke Galeri' }}
          </button>
          <a href="{{ route('admin.projects.index') }}" class="btn btn-label-secondary">Batal</a>
        </div>
      </div>
    </div>
  </form>

  {{-- Galeri hanya tersedia setelah proyek punya ID --}}
  @if ($isEdit)
    <div class="card mt-4">
      <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
          <h5 class="mb-1">Galeri Foto</h5>
          <p class="mb-0 text-body-secondary small">
            Seret foto untuk mengubah urutan tampil di halaman detail proyek.
          </p>
        </div>
        <span class="badge bg-label-info">{{ $project->images->count() }} foto</span>
      </div>

      <div class="card-body">
        <form method="POST" action="{{ route('admin.projects.images.store', $project) }}"
              enctype="multipart/form-data" class="mb-4">
          @csrf
          <label class="form-label" for="images">Tambah Foto</label>
          <div class="input-group">
            <input type="file" class="form-control @error('images') is-invalid @enderror"
                   id="images" name="images[]" accept="image/*" multiple required />
            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-upload me-1"></i> Unggah
            </button>
          </div>
          @error('images') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
          @error('images.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
          <div class="form-text">Bisa pilih beberapa file sekaligus (maksimal 20, masing-masing 6 MB).</div>
        </form>

        @if ($project->images->isEmpty())
          <div class="text-center py-5 border rounded" style="border-style:dashed !important">
            <i class="icon-base ti tabler-photo-plus icon-48px text-body-secondary mb-2 d-block"></i>
            <p class="mb-0 text-body-secondary">Belum ada foto di galeri proyek ini.</p>
          </div>
        @else
          <div class="gallery-grid" id="gallery-sortable" data-reorder-url="{{ route('admin.projects.images.reorder', $project) }}">
            @foreach ($project->images as $image)
              <div class="gallery-item" data-id="{{ $image->id }}">
                <img src="{{ upload_url($image->path) }}" alt="{{ $image->caption ?: $project->title }}" />
                <div class="gallery-actions">
                  <form method="POST" action="{{ route('admin.projects.images.destroy', $image) }}">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-icon btn-danger" title="Hapus foto"
                            data-confirm-delete="Foto ini akan dihapus permanen.">
                      <i class="icon-base ti tabler-trash"></i>
                    </button>
                  </form>
                </div>
                <div class="p-2 d-flex align-items-center gap-2">
                  <i class="icon-base ti tabler-grip-vertical text-body-secondary drag-handle"></i>
                  <small class="text-body-secondary text-truncate">Urutan {{ $loop->iteration }}</small>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  @endif
@endsection

@push('scripts')
@if ($isEdit && $project->images->isNotEmpty())
<script src="{{ asset('assets/vendor/libs/sortablejs/sortable.js') }}"></script>
<script>
  (function () {
    const grid = document.getElementById('gallery-sortable');
    if (!grid || typeof Sortable === 'undefined') return;

    Sortable.create(grid, {
      animation: 150,
      handle: '.drag-handle',
      ghostClass: 'sortable-ghost',
      onEnd: function () {
        const order = Array.from(grid.querySelectorAll('.gallery-item')).map(el => el.dataset.id);

        fetch(grid.dataset.reorderUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ order: order })
        }).then(function (response) {
          if (!response.ok) throw new Error('gagal');
          grid.querySelectorAll('.gallery-item small').forEach(function (el, index) {
            el.textContent = 'Urutan ' + (index + 1);
          });
        }).catch(function () {
          Swal.fire({
            icon: 'error',
            title: 'Urutan gagal disimpan',
            text: 'Silakan muat ulang halaman dan coba lagi.',
            customClass: { confirmButton: 'btn btn-primary' },
            buttonsStyling: false
          });
        });
      }
    });
  })();
</script>
@endif
@endpush
