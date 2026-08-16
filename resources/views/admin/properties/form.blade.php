@extends('layouts.admin')

@php $isEdit = $property->exists; @endphp

@section('title', $isEdit ? 'Ubah Properti' : 'Tambah Properti')
@section('page-title', $isEdit ? 'Ubah Properti' : 'Tambah Properti')
@section('page-subtitle', $isEdit ? $property->title : 'Isi detail properti, galeri foto bisa ditambahkan setelah disimpan')

@section('page-actions')
  <a href="{{ route('admin.properties.index') }}" class="btn btn-label-secondary">
    <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
  </a>
@endsection

@section('content')
  <form method="POST"
        action="{{ $isEdit ? route('admin.properties.update', $property) : route('admin.properties.store') }}"
        enctype="multipart/form-data">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="row g-4">
      <div class="col-lg-8">

        {{-- ---------------- Identitas ---------------- --}}
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Informasi Properti</h5></div>
          <div class="card-body">

            <div class="mb-4">
              <label class="form-label" for="title">Nama Properti <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('title') is-invalid @enderror"
                     id="title" name="title" value="{{ old('title', $property->title) }}" required
                     placeholder="Rumah 2 Lantai Bintaro Sektor 9" />
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="slug">Slug URL</label>
              <input type="text" class="form-control @error('slug') is-invalid @enderror"
                     id="slug" name="slug" value="{{ old('slug', $property->slug) }}"
                     placeholder="otomatis dari nama properti" />
              @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <label class="form-label" for="type">Jenis Properti</label>
                <input type="text" class="form-control" id="type" name="type" list="type-list"
                       value="{{ old('type', $property->type) }}" placeholder="Rumah" />
                <datalist id="type-list">
                  <option value="Rumah"></option>
                  <option value="Apartemen"></option>
                  <option value="Ruko"></option>
                  <option value="Tanah"></option>
                  <option value="Villa"></option>
                  <option value="Kantor"></option>
                  <option value="Gudang"></option>
                </datalist>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label" for="location">Lokasi</label>
                <input type="text" class="form-control" id="location" name="location"
                       value="{{ old('location', $property->location) }}" placeholder="Bintaro, Tangerang Selatan" />
                <div class="form-text">Kota atau kawasan — dipakai pengunjung untuk menyaring.</div>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label" for="address">Alamat Lengkap</label>
              <textarea class="form-control @error('address') is-invalid @enderror"
                        id="address" name="address" rows="2"
                        placeholder="Jl. Contoh Raya No. 12, Sektor 9, Bintaro">{{ old('address', $property->address) }}</textarea>
              @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="excerpt">Ringkasan</label>
              <textarea class="form-control @error('excerpt') is-invalid @enderror"
                        id="excerpt" name="excerpt" rows="2"
                        placeholder="Kalimat singkat yang muncul di kartu daftar properti.">{{ old('excerpt', $property->excerpt) }}</textarea>
              @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-2">
              <label class="form-label" for="description">Deskripsi Lengkap</label>
              <textarea class="form-control @error('description') is-invalid @enderror"
                        id="description" name="description" rows="9"
                        placeholder="Ceritakan kondisi bangunan, keunggulan lokasi, akses, dan fasilitas di sekitarnya.">{{ old('description', $property->description) }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="form-text">Pisahkan antar paragraf dengan baris kosong.</div>
            </div>

          </div>
        </div>

        {{-- ---------------- Spesifikasi ---------------- --}}
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-1">Spesifikasi</h5>
            <p class="mb-0 text-body-secondary small">
              Kolom yang dikosongkan tidak akan ditampilkan di halaman properti.
            </p>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3 mb-4">
                <label class="form-label" for="land_area">Luas Tanah (m²)</label>
                <input type="number" class="form-control @error('land_area') is-invalid @enderror"
                       id="land_area" name="land_area" min="0"
                       value="{{ old('land_area', $property->land_area) }}" placeholder="180" />
                @error('land_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3 mb-4">
                <label class="form-label" for="building_area">Luas Bangunan (m²)</label>
                <input type="number" class="form-control @error('building_area') is-invalid @enderror"
                       id="building_area" name="building_area" min="0"
                       value="{{ old('building_area', $property->building_area) }}" placeholder="150" />
                @error('building_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3 mb-4">
                <label class="form-label" for="bedrooms">Kamar Tidur</label>
                <input type="number" class="form-control" id="bedrooms" name="bedrooms" min="0" max="255"
                       value="{{ old('bedrooms', $property->bedrooms) }}" placeholder="3" />
              </div>
              <div class="col-md-3 mb-4">
                <label class="form-label" for="bathrooms">Kamar Mandi</label>
                <input type="number" class="form-control" id="bathrooms" name="bathrooms" min="0" max="255"
                       value="{{ old('bathrooms', $property->bathrooms) }}" placeholder="2" />
              </div>

              <div class="col-md-3 mb-4">
                <label class="form-label" for="carports">Carport</label>
                <input type="number" class="form-control" id="carports" name="carports" min="0" max="255"
                       value="{{ old('carports', $property->carports) }}" placeholder="1" />
              </div>
              <div class="col-md-3 mb-4">
                <label class="form-label" for="floors">Jumlah Lantai</label>
                <input type="number" class="form-control" id="floors" name="floors" min="0" max="255"
                       value="{{ old('floors', $property->floors) }}" placeholder="2" />
              </div>
              <div class="col-md-3 mb-4">
                <label class="form-label" for="certificate">Sertifikat</label>
                <input type="text" class="form-control" id="certificate" name="certificate" list="certificate-list"
                       value="{{ old('certificate', $property->certificate) }}" placeholder="SHM" />
                <datalist id="certificate-list">
                  <option value="SHM"></option>
                  <option value="HGB"></option>
                  <option value="SHSRS"></option>
                  <option value="PPJB"></option>
                  <option value="AJB"></option>
                </datalist>
              </div>
              <div class="col-md-3 mb-4">
                <label class="form-label" for="year_built">Tahun Dibangun</label>
                <input type="number" class="form-control @error('year_built') is-invalid @enderror"
                       id="year_built" name="year_built" min="1900" max="{{ date('Y') + 5 }}"
                       value="{{ old('year_built', $property->year_built) }}" placeholder="{{ date('Y') }}" />
                @error('year_built') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>
        </div>

        @include('admin.partials.translation-card', [
            'model'  => $property,
            'fields' => [
                'title'       => ['Property Name', 'text', 'Two-Storey House, Bintaro Sector 9'],
                'type'        => ['Property Type', 'text', 'House'],
                'location'    => ['Location', 'text', 'Bintaro, South Tangerang'],
                'certificate' => ['Certificate', 'text', 'Freehold (SHM)'],
                'price_note'  => ['Price Note', 'text', '/ month'],
                'excerpt'     => ['Summary', 'textarea', 'A short line shown on the property card.'],
                'description' => ['Full Description', 'textarea', 'Condition, location advantages, access, and nearby facilities.'],
            ],
        ])
      </div>

      <div class="col-lg-4">

        {{-- ---------------- Harga ---------------- --}}
        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Harga & Status</h5></div>
          <div class="card-body">

            <div class="mb-4">
              <label class="form-label" for="listing_status">Status <span class="text-danger">*</span></label>
              <select class="form-select @error('listing_status') is-invalid @enderror"
                      id="listing_status" name="listing_status" required>
                @foreach (\App\Models\Property::STATUSES as $status)
                  <option value="{{ $status }}"
                          {{ old('listing_status', $property->listing_status ?? 'dijual') === $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                  </option>
                @endforeach
              </select>
              @error('listing_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="form-text">
                Status <strong>Terjual</strong> dan <strong>Tersewa</strong> tetap tampil di halaman
                properti dengan label, tetapi tidak lagi muncul di beranda.
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label" for="price">Harga</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control @error('price') is-invalid @enderror"
                       id="price" name="price" inputmode="numeric"
                       value="{{ old('price', $property->price !== null ? number_format($property->price, 0, ',', '.') : '') }}"
                       placeholder="1.500.000.000" />
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="form-text">
                Titik pemisah ribuan boleh ditulis atau tidak — keduanya diterima.
                Kosongkan bila ingin tampil sebagai &ldquo;Harga atas permintaan&rdquo;.
              </div>
            </div>

            <div class="mb-0">
              <label class="form-label" for="price_note">Keterangan Harga</label>
              <input type="text" class="form-control" id="price_note" name="price_note"
                     value="{{ old('price_note', $property->price_note) }}" placeholder="/ bulan" />
              <div class="form-text">Opsional. Tampil tepat di belakang harga, mis. &ldquo;/ bulan&rdquo; atau &ldquo;Nego&rdquo;.</div>
            </div>

          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Gambar Sampul</h5></div>
          <div class="card-body">
            <img id="cover-preview" src="{{ upload_url($property->cover_image) }}"
                 class="image-preview mb-3 {{ $property->cover_image ? '' : 'd-none' }}" alt="Pratinjau" />
            <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                   name="cover_image" accept="image/*" data-preview="#cover-preview" />
            @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Foto utama properti. Maksimal 6 MB.</div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h5 class="mb-0">Publikasi</h5></div>
          <div class="card-body">
            <div class="mb-4">
              <label class="form-label" for="sort_order">Urutan Tampil</label>
              <input type="number" class="form-control" id="sort_order" name="sort_order" min="0"
                     value="{{ old('sort_order', $property->sort_order ?? 0) }}" />
              <div class="form-text">Angka lebih kecil tampil lebih dulu.</div>
            </div>

            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                     {{ old('is_active', $property->exists ? $property->is_active : true) ? 'checked' : '' }} />
              <label class="form-check-label" for="is_active">Tampilkan di website</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                     {{ old('is_featured', $property->is_featured) ? 'checked' : '' }} />
              <label class="form-check-label" for="is_featured">Tampilkan di beranda (unggulan)</label>
            </div>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-1"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan & Lanjut ke Galeri' }}
          </button>
          <a href="{{ route('admin.properties.index') }}" class="btn btn-label-secondary">Batal</a>
        </div>
      </div>
    </div>
  </form>

  {{-- Galeri hanya tersedia setelah properti punya ID --}}
  @if ($isEdit)
    <div class="card mt-4">
      <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
          <h5 class="mb-1">Galeri Foto</h5>
          <p class="mb-0 text-body-secondary small">
            Seret foto untuk mengubah urutan tampil di halaman detail properti.
          </p>
        </div>
        <span class="badge bg-label-info">{{ $property->images->count() }} foto</span>
      </div>

      <div class="card-body">
        <form method="POST" action="{{ route('admin.properties.images.store', $property) }}"
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

        @if ($property->images->isEmpty())
          <div class="text-center py-5 border rounded" style="border-style:dashed !important">
            <i class="icon-base ti tabler-photo-plus icon-48px text-body-secondary mb-2 d-block"></i>
            <p class="mb-0 text-body-secondary">Belum ada foto di galeri properti ini.</p>
          </div>
        @else
          <div class="gallery-grid" id="gallery-sortable" data-reorder-url="{{ route('admin.properties.images.reorder', $property) }}">
            @foreach ($property->images as $image)
              <div class="gallery-item" data-id="{{ $image->id }}">
                <img src="{{ upload_url($image->path) }}" alt="{{ $image->caption ?: $property->title }}" />
                <div class="gallery-actions">
                  <form method="POST" action="{{ route('admin.properties.images.destroy', $image) }}">
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
<script>
  // Harga diberi titik pemisah ribuan sambil diketik supaya angka besar
  // tetap mudah dibaca. Nilai bersihnya dibentuk ulang di server.
  (function () {
    const input = document.getElementById('price');
    if (!input) return;

    input.addEventListener('input', function () {
      const angka = input.value.replace(/\D/g, '');
      input.value = angka ? Number(angka).toLocaleString('id-ID') : '';
    });
  })();
</script>
@if ($isEdit && $property->images->isNotEmpty())
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
