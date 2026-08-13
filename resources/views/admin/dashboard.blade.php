@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan konten website ' . setting('site.name', 'Dekorasi.me'))

@section('content')
  <div class="row g-4 mb-4">
    @php
        $cards = [
            ['label' => 'Total Proyek',    'value' => $stats['projects'],       'icon' => 'tabler-building-arch', 'route' => 'admin.projects.index'],
            ['label' => 'Proyek Tampil',   'value' => $stats['projectsActive'], 'icon' => 'tabler-eye',           'route' => 'admin.projects.index'],
            ['label' => 'Paket Layanan',   'value' => $stats['services'],       'icon' => 'tabler-package',       'route' => 'admin.services.index'],
            ['label' => 'Slide Aktif',     'value' => $stats['sliders'],        'icon' => 'tabler-slideshow',     'route' => 'admin.sliders.index'],
        ];
    @endphp

    @foreach ($cards as $card)
      <div class="col-sm-6 col-xl-3">
        <a href="{{ route($card['route']) }}" class="text-decoration-none">
          <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <p class="mb-1 text-body-secondary small">{{ $card['label'] }}</p>
                <h4 class="mb-0">{{ $card['value'] }}</h4>
              </div>
              <span class="stat-icon">
                <i class="icon-base ti {{ $card['icon'] }}"></i>
              </span>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Proyek Terbaru</h5>
          <a href="{{ route('admin.projects.create') }}" class="btn btn-sm btn-primary">
            <i class="icon-base ti tabler-plus me-1"></i> Tambah
          </a>
        </div>

        @if ($recentProjects->isEmpty())
          <div class="card-body text-center py-6">
            <i class="icon-base ti tabler-photo-off icon-48px text-body-secondary mb-3 d-block"></i>
            <p class="mb-3">Belum ada proyek yang ditambahkan.</p>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Tambah Proyek Pertama</a>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Proyek</th>
                  <th>Kategori</th>
                  <th>Status</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($recentProjects as $project)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img src="{{ upload_url($project->cover_image, asset('img/placeholder.svg')) }}"
                             alt="{{ $project->title }}" width="48" height="36"
                             style="object-fit:cover;border-radius:6px" />
                        <div>
                          <span class="fw-medium d-block">{{ $project->title }}</span>
                          <small class="text-body-secondary">{{ $project->location ?: '—' }}</small>
                        </div>
                      </div>
                    </td>
                    <td>{{ $project->category ?: '—' }}</td>
                    <td>
                      <span class="badge bg-label-{{ $project->is_active ? 'success' : 'secondary' }}">
                        {{ $project->is_active ? 'Tampil' : 'Tersembunyi' }}
                      </span>
                    </td>
                    <td class="text-end">
                      <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-icon btn-text-secondary">
                        <i class="icon-base ti tabler-edit"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header"><h5 class="mb-0">Pintasan</h5></div>
        <div class="card-body d-grid gap-2">
          <a href="{{ route('admin.sliders.index') }}" class="btn btn-label-primary d-flex align-items-center justify-content-start gap-2">
            <i class="icon-base ti tabler-slideshow"></i> Kelola Slider Beranda
          </a>
          <a href="{{ route('admin.services.index') }}" class="btn btn-label-primary d-flex align-items-center justify-content-start gap-2">
            <i class="icon-base ti tabler-package"></i> Kelola Paket Layanan
          </a>
          <a href="{{ route('admin.about.edit') }}" class="btn btn-label-primary d-flex align-items-center justify-content-start gap-2">
            <i class="icon-base ti tabler-info-circle"></i> Edit Tentang Kami
          </a>
          <a href="{{ route('admin.settings.edit') }}" class="btn btn-label-primary d-flex align-items-center justify-content-start gap-2">
            <i class="icon-base ti tabler-settings"></i> Pengaturan Situs
          </a>
          <hr class="my-2" />
          <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
            <i class="icon-base ti tabler-external-link"></i> Buka Website
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
