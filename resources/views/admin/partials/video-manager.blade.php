{{--
  Pengelola video, dipakai bersama form Proyek dan form Properti.

  @param \Illuminate\Database\Eloquent\Model $model  Proyek atau Properti (sudah tersimpan)
  @param string $jenis                               'projects' atau 'properties'
--}}
@php
    $batasKb   = batas_unggah_kb();
    $batasTeks = ukuran_terbaca($batasKb);
    $videos    = $model->videos;
@endphp

<div class="card mt-4">
  <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h5 class="mb-1">Video</h5>
      <p class="mb-0 text-body-secondary small">
        Unggah berkas video, atau tempelkan tautan YouTube / Vimeo.
        Seret untuk mengubah urutan tampil di website.
      </p>
    </div>
    <span class="badge bg-label-info">{{ $videos->count() }} video</span>
  </div>

  <div class="card-body">

    <form method="POST" action="{{ route('admin.videos.store', [$jenis, $model->getKey()]) }}"
          enctype="multipart/form-data" class="mb-4" id="form-video">
      @csrf

      {{-- Pemilih sumber video --}}
      <ul class="nav nav-pills mb-4" role="tablist">
        <li class="nav-item">
          <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#video-unggah" role="tab">
            <i class="icon-base ti tabler-upload me-1"></i> Unggah Berkas
          </button>
        </li>
        <li class="nav-item">
          <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#video-tautan" role="tab">
            <i class="icon-base ti tabler-brand-youtube me-1"></i> Tautan YouTube / Vimeo
          </button>
        </li>
      </ul>

      <input type="hidden" name="source" id="video-source" value="upload" />

      <div class="tab-content p-0">
        <div class="tab-pane fade show active" id="video-unggah" role="tabpanel">
          <label class="form-label" for="video-file">Berkas Video</label>
          <input type="file" class="form-control @error('file') is-invalid @enderror"
                 id="video-file" name="file" accept=".mp4,video/mp4"
                 data-batas-kb="{{ $batasKb }}" data-batas-teks="{{ $batasTeks }}" />
          @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text">
            Format <strong>MP4</strong>. Batas server saat ini
            <strong>{{ $batasTeks }}</strong> per berkas.
            @if ($batasKb < 20480)
              <span class="text-warning">
                Batas ini masih kecil untuk video — naikkan <code>upload_max_filesize</code>
                dan <code>post_max_size</code> di cPanel &rarr; MultiPHP INI Editor.
              </span>
            @endif
          </div>
          <div class="text-danger small mt-1 d-none" id="video-terlalu-besar"></div>
        </div>

        <div class="tab-pane fade" id="video-tautan" role="tabpanel">
          <label class="form-label" for="video-url">Tautan Video</label>
          <input type="url" class="form-control @error('url') is-invalid @enderror"
                 id="video-url" name="url" value="{{ old('url') }}"
                 placeholder="https://www.youtube.com/watch?v=..." />
          @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text">
            Menerima alamat YouTube (termasuk youtu.be dan Shorts) maupun Vimeo.
            Pilihan ini tidak memakan kuota penyimpanan hosting.
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-4 mb-4">
          <label class="form-label" for="video-title">Judul Video</label>
          <input type="text" class="form-control" id="video-title" name="title"
                 value="{{ old('title') }}" placeholder="Walkthrough Ruang Tamu" />
          <div class="form-text">Opsional.</div>
        </div>
        <div class="col-md-4 mb-4">
          <label class="form-label" for="video-title-en">Judul (English)</label>
          <input type="text" class="form-control" id="video-title-en" name="title_en"
                 value="{{ old('title_en') }}" placeholder="Living Room Walkthrough" />
          <div class="form-text">Opsional.</div>
        </div>
        <div class="col-md-4 mb-4">
          <label class="form-label" for="video-poster">Gambar Sampul</label>
          <input type="file" class="form-control @error('poster') is-invalid @enderror"
                 id="video-poster" name="poster" accept="image/*" />
          @error('poster') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text">Opsional, maksimal 4 MB.</div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" id="video-submit">
        <i class="icon-base ti tabler-plus me-1"></i> Tambah Video
      </button>
    </form>

    @if ($videos->isEmpty())
      <div class="text-center py-5 border rounded" style="border-style:dashed !important">
        <i class="icon-base ti tabler-video-plus icon-48px text-body-secondary mb-2 d-block"></i>
        <p class="mb-0 text-body-secondary">Belum ada video.</p>
      </div>
    @else
      <div class="video-list" id="video-sortable"
           data-reorder-url="{{ route('admin.videos.reorder', [$jenis, $model->getKey()]) }}">
        @foreach ($videos as $video)
          <div class="video-row" data-id="{{ $video->id }}">
            <i class="icon-base ti tabler-grip-vertical text-body-secondary drag-handle"></i>

            <div class="video-thumb">
              @if ($video->posterUrl())
                <img src="{{ $video->posterUrl() }}" alt="{{ $video->title }}" />
              @else
                <i class="icon-base ti tabler-video"></i>
              @endif
            </div>

            <div class="flex-grow-1 min-w-0">
              <span class="fw-medium d-block text-truncate">{{ $video->title ?: 'Video ' . $loop->iteration }}</span>
              <small class="text-body-secondary text-truncate d-block">
                @if ($video->diunggah())
                  Berkas unggahan &middot; {{ basename($video->path) }}
                @else
                  {{ ucfirst($video->source) }} &middot; {{ $video->url }}
                @endif
              </small>
            </div>

            <span class="badge bg-label-{{ $video->diunggah() ? 'primary' : 'info' }} flex-shrink-0">
              {{ $video->diunggah() ? 'Unggahan' : ucfirst($video->source) }}
            </span>

            <form method="POST" action="{{ route('admin.videos.destroy', $video) }}" class="flex-shrink-0">
              @csrf @method('DELETE')
              <button type="button" class="btn btn-sm btn-icon btn-text-danger" title="Hapus video"
                      data-confirm-delete="Video ini akan dihapus permanen.">
                <i class="icon-base ti tabler-trash"></i>
              </button>
            </form>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  (function () {
    const form = document.getElementById('form-video');
    if (!form) return;

    const sumber = document.getElementById('video-source');
    const berkas = document.getElementById('video-file');
    const tautan = document.getElementById('video-url');
    const peringatan = document.getElementById('video-terlalu-besar');
    const tombol = document.getElementById('video-submit');

    // Tab menentukan sumber yang dikirim, sekaligus mengosongkan isian tab lain
    // supaya tidak ada dua sumber terkirim bersamaan.
    document.querySelectorAll('[data-bs-target="#video-unggah"], [data-bs-target="#video-tautan"]')
      .forEach(function (tab) {
        tab.addEventListener('shown.bs.tab', function () {
          const unggah = tab.dataset.bsTarget === '#video-unggah';
          sumber.value = unggah ? 'upload' : 'youtube';
          if (unggah) { tautan.value = ''; } else { berkas.value = ''; sembunyikanPeringatan(); }
        });
      });

    function sembunyikanPeringatan() {
      peringatan.classList.add('d-none');
      tombol.disabled = false;
    }

    /*
     * Ukuran diperiksa di browser sebelum dikirim. Bila melebihi post_max_size,
     * PHP membuang seluruh isi POST — termasuk token CSRF — sehingga yang muncul
     * adalah galat 419 yang membingungkan, bukan pesan "file terlalu besar".
     */
    berkas.addEventListener('change', function () {
      sembunyikanPeringatan();
      const file = berkas.files[0];
      if (!file) return;

      const batasKb = parseInt(berkas.dataset.batasKb, 10);
      if (file.size / 1024 > batasKb) {
        peringatan.textContent = 'Ukuran video ' + (file.size / 1048576).toFixed(1)
          + ' MB, melebihi batas server ' + berkas.dataset.batasTeks
          + '. Naikkan batas di cPanel, atau pakai tautan YouTube / Vimeo.';
        peringatan.classList.remove('d-none');
        tombol.disabled = true;
      }
    });
  })();
</script>
@if ($videos->isNotEmpty())
<script src="{{ asset('assets/vendor/libs/sortablejs/sortable.js') }}"></script>
<script>
  (function () {
    const list = document.getElementById('video-sortable');
    if (!list || typeof Sortable === 'undefined') return;

    Sortable.create(list, {
      animation: 150,
      handle: '.drag-handle',
      ghostClass: 'sortable-ghost',
      onEnd: function () {
        const order = Array.from(list.querySelectorAll('.video-row')).map(el => el.dataset.id);

        fetch(list.dataset.reorderUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ order: order })
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
