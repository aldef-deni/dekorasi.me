@extends('layouts.admin')

@section('title', 'Layanan')
@section('page-title', 'Layanan')
@section('page-subtitle', 'Daftar jasa yang Anda tawarkan, tampil di beranda dan halaman Layanan')

@section('page-actions')
  <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
    <i class="icon-base ti tabler-plus me-1"></i> Tambah Layanan
  </a>
@endsection

@section('content')
  <div class="card">
    @if ($services->isEmpty())
      <div class="card-body text-center py-6">
        <i class="icon-base ti tabler-tools icon-48px text-body-secondary mb-3 d-block"></i>
        <p class="mb-3">Belum ada layanan yang ditambahkan.</p>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Tambah Layanan</a>
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th style="width:60px">Ikon</th>
              <th>Layanan</th>
              <th style="width:90px">Urutan</th>
              <th style="width:130px">Status</th>
              <th style="width:120px" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($services as $service)
              <tr>
                <td>
                  <span class="stat-icon" style="width:36px;height:36px;font-size:1.1rem">
                    <i class="icon-base ti {{ $service->icon ?: 'tabler-sofa' }}"></i>
                  </span>
                </td>
                <td>
                  <span class="fw-medium d-block">{{ $service->title }}</span>
                  <small class="text-body-secondary">{{ Str::limit($service->excerpt, 80) ?: '/'.$service->slug }}</small>
                </td>
                <td>{{ $service->sort_order }}</td>
                <td>
                  <span class="badge bg-label-{{ $service->is_active ? 'success' : 'secondary' }}">
                    {{ $service->is_active ? 'Tampil' : 'Tersembunyi' }}
                  </span>
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                    <i class="icon-base ti tabler-edit"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-icon btn-text-danger" title="Hapus"
                            data-confirm-delete="Layanan &quot;{{ $service->title }}&quot; akan dihapus permanen.">
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
