{{--
  Kartu isian versi Inggris.

  @param \Illuminate\Database\Eloquent\Model $model  Model yang memakai HasTranslations
  @param array $fields  ['nama_kolom' => ['Label', 'jenis', 'placeholder']]
                        jenis: text | textarea | textarea-lg
--}}
@php
    $sudahAda = $model->exists && $model->hasTranslation('en');
@endphp

<div class="card mb-4">
  <div class="card-header d-flex align-items-start justify-content-between gap-3">
    <div>
      <h5 class="mb-1 d-flex align-items-center gap-2">
        <i class="icon-base ti tabler-language text-primary"></i>
        Versi Bahasa Inggris
      </h5>
      <p class="mb-0 text-body-secondary small">
        Tampil saat pengunjung memilih bahasa Inggris. Kolom yang dikosongkan
        otomatis memakai teks bahasa Indonesia.
      </p>
    </div>

    <span class="badge bg-label-{{ $sudahAda ? 'success' : 'secondary' }} flex-shrink-0">
      {{ $sudahAda ? 'Sudah diisi' : 'Belum diisi' }}
    </span>
  </div>

  <div class="card-body">
    <div class="row">
      @foreach ($fields as $name => [$label, $jenis, $placeholder])
        @php
            $nilai = old("en.{$name}", $model->translation('en', $name));
            $lebar = $jenis === 'text' ? 'col-md-6' : 'col-12';
        @endphp

        <div class="{{ $lebar }} mb-4">
          <label class="form-label" for="en_{{ $name }}">{{ $label }}</label>

          @if ($jenis === 'text')
            <input type="text" class="form-control" id="en_{{ $name }}"
                   name="en[{{ $name }}]" value="{{ $nilai }}" placeholder="{{ $placeholder }}" />
          @else
            <textarea class="form-control {{ $jenis === 'textarea-lg' ? 'font-monospace' : '' }}"
                      id="en_{{ $name }}" name="en[{{ $name }}]"
                      rows="{{ $jenis === 'textarea-lg' ? 9 : 3 }}"
                      placeholder="{{ $placeholder }}">{{ $nilai }}</textarea>
          @endif

          @if ($name === 'features')
            <div class="form-text">Satu poin per baris, sama seperti versi Indonesia.</div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</div>
