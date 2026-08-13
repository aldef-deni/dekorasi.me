@extends('layouts.admin')

@section('title', 'Paket Layanan')
@section('page-title', 'Paket Layanan')
@section('page-subtitle', 'Paket yang ditawarkan ke klien — tampil di beranda dan halaman Paket Layanan')

@section('page-actions')
  <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
    <i class="icon-base ti tabler-plus me-1"></i> Tambah Paket
  </a>
@endsection

@section('content')
  <div class="card">
    @if ($services->isEmpty())
      <div class="card-body text-center py-6">
        <i class="icon-base ti tabler-package icon-48px text-body-secondary mb-3 d-block"></i>
        <p class="mb-3">Belum ada paket layanan yang ditambahkan.</p>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Tambah Paket</a>
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th style="width:60px">Ikon</th>
              <th>Paket</th>
              <th style="width:150px">Harga</th>
              <th style="width:80px">Isi</th>
              <th style="width:90px">Urutan</th>
              <th style="width:170px">Status</th>
              <th style="width:120px" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($services as $service)
              <tr>
                <td>
                  <span class="stat-icon" style="width:36px;height:36px;font-size:1.1rem">
                    <i class="icon-base ti {{ $service->icon ?: 'tabler-pencil' }}"></i>
                  </span>
                </td>
                <td>
                  <span class="fw-medium d-block">{{ $service->title }}</span>
                  <small class="text-body-secondary">
                    {{ $service->subtitle ?: Str::limit($service->excerpt, 70) ?: '/'.$service->slug }}
                  </small>
                </td>
                <td>
                  <small class="text-body-secondary">{{ $service->price ?: '—' }}</small>
                </td>
                <td>
                  <span class="badge bg-label-info">{{ $service->featureList()->count() }}</span>
                </td>
                <td>{{ $service->sort_order }}</td>
                <td>
                  <span class="badge bg-label-{{ $service->is_active ? 'success' : 'secondary' }}">
                    {{ $service->is_active ? 'Tampil' : 'Tersembunyi' }}
                  </span>
                  @if ($service->is_featured)
                    <span class="badge bg-label-warning">Unggulan</span>
                  @endif
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                    <i class="icon-base ti tabler-edit"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-icon btn-text-danger" title="Hapus"
                            data-confirm-delete="Paket &quot;{{ $service->title }}&quot; akan dihapus permanen.">
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
