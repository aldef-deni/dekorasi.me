@extends('layouts.admin')

@section('title', 'Proyek')
@section('page-title', 'Portofolio Proyek')
@section('page-subtitle', 'Kelola proyek beserta galeri fotonya')

@section('page-actions')
  <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
    <i class="icon-base ti tabler-plus me-1"></i> Tambah Proyek
  </a>
@endsection

@section('content')
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="form-label" for="q">Cari Proyek</label>
          <input type="text" class="form-control" id="q" name="q" value="{{ request('q') }}"
                 placeholder="Nama proyek…" />
        </div>
        <div class="col-md-4">
          <label class="form-label" for="category">Kategori</label>
          <select class="form-select" id="category" name="category">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
              <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                {{ $category }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-grid">
          <button type="submit" class="btn btn-label-primary">
            <i class="icon-base ti tabler-search me-1"></i> Filter
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    @if ($projects->isEmpty())
      <div class="card-body text-center py-6">
        <i class="icon-base ti tabler-building-arch icon-48px text-body-secondary mb-3 d-block"></i>
        <p class="mb-3">
          {{ request()->hasAny(['q', 'category']) ? 'Tidak ada proyek yang cocok dengan filter.' : 'Belum ada proyek yang ditambahkan.' }}
        </p>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Tambah Proyek</a>
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th style="width:110px">Sampul</th>
              <th>Proyek</th>
              <th style="width:130px">Kategori</th>
              <th style="width:80px">Foto</th>
              <th style="width:150px">Status</th>
              <th style="width:120px" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($projects as $project)
              <tr>
                <td>
                  <img src="{{ upload_url($project->cover_image, asset('img/placeholder.svg')) }}"
                       alt="{{ $project->title }}" width="88" height="54"
                       style="object-fit:cover;border-radius:6px" />
                </td>
                <td>
                  <span class="fw-medium d-block">{{ $project->title }}</span>
                  <small class="text-body-secondary">
                    {{ collect([$project->location, $project->year])->filter()->implode(' • ') ?: '—' }}
                  </small>
                </td>
                <td>{{ $project->category ?: '—' }}</td>
                <td>
                  <span class="badge bg-label-info">{{ $project->images_count }}</span>
                </td>
                <td>
                  <span class="badge bg-label-{{ $project->is_active ? 'success' : 'secondary' }}">
                    {{ $project->is_active ? 'Tampil' : 'Tersembunyi' }}
                  </span>
                  @if ($project->is_featured)
                    <span class="badge bg-label-warning">Unggulan</span>
                  @endif
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                    <i class="icon-base ti tabler-edit"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-icon btn-text-danger" title="Hapus"
                            data-confirm-delete="Proyek &quot;{{ $project->title }}&quot; beserta seluruh fotonya akan dihapus permanen.">
                      <i class="icon-base ti tabler-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if ($projects->hasPages())
        <div class="card-body pt-3">{{ $projects->links() }}</div>
      @endif
    @endif
  </div>
@endsection
