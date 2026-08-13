@extends('layouts.admin')

@section('title', 'Slider Beranda')
@section('page-title', 'Slider Beranda')
@section('page-subtitle', 'Gambar dan teks besar yang tampil di bagian paling atas halaman depan')

@section('page-actions')
  <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
    <i class="icon-base ti tabler-plus me-1"></i> Tambah Slide
  </a>
@endsection

@section('content')
  <div class="card">
    @if ($sliders->isEmpty())
      <div class="card-body text-center py-6">
        <i class="icon-base ti tabler-slideshow icon-48px text-body-secondary mb-3 d-block"></i>
        <p class="mb-3">Belum ada slide. Tambahkan minimal satu agar beranda tidak kosong.</p>
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">Tambah Slide</a>
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th style="width:110px">Gambar</th>
              <th>Judul</th>
              <th style="width:90px">Urutan</th>
              <th style="width:130px">Status</th>
              <th style="width:120px" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($sliders as $slider)
              <tr>
                <td>
                  <img src="{{ upload_url($slider->image, asset('img/placeholder.svg')) }}"
                       alt="{{ $slider->title }}" width="88" height="54"
                       style="object-fit:cover;border-radius:6px" />
                </td>
                <td>
                  <span class="fw-medium d-block">{{ $slider->title }}</span>
                  <small class="text-body-secondary">{{ Str::limit($slider->subtitle, 70) ?: '—' }}</small>
                </td>
                <td>{{ $slider->sort_order }}</td>
                <td>
                  <span class="badge bg-label-{{ $slider->is_active ? 'success' : 'secondary' }}">
                    {{ $slider->is_active ? 'Tampil' : 'Tersembunyi' }}
                  </span>
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                    <i class="icon-base ti tabler-edit"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-icon btn-text-danger" title="Hapus"
                            data-confirm-delete="Slide &quot;{{ $slider->title }}&quot; akan dihapus permanen.">
                      <i class="icon-base ti tabler-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
@endsection
