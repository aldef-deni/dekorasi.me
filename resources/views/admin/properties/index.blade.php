@extends('layouts.admin')

@section('title', 'Properti')
@section('page-title', 'Penjualan Properti')
@section('page-subtitle', 'Kelola daftar properti beserta harga dan galeri fotonya')

@section('page-actions')
  <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">
    <i class="icon-base ti tabler-plus me-1"></i> Tambah Properti
  </a>
@endsection

@section('content')
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-5">
          <label class="form-label" for="q">Cari Properti</label>
          <input type="text" class="form-control" id="q" name="q" value="{{ request('q') }}"
                 placeholder="Nama properti…" />
        </div>
        <div class="col-md-3">
          <label class="form-label" for="type">Jenis</label>
          <select class="form-select" id="type" name="type">
            <option value="">Semua jenis</option>
            @foreach ($types as $type)
              <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label" for="status">Status</label>
          <select class="form-select" id="status" name="status">
            <option value="">Semua</option>
            @foreach (\App\Models\Property::STATUSES as $status)
              <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                {{ ucfirst($status) }}
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
    @if ($properties->isEmpty())
      <div class="card-body text-center py-6">
        <i class="icon-base ti tabler-home-dollar icon-48px text-body-secondary mb-3 d-block"></i>
        <p class="mb-3">
          {{ request()->hasAny(['q', 'type', 'status'])
              ? 'Tidak ada properti yang cocok dengan filter.'
              : 'Belum ada properti yang ditambahkan.' }}
        </p>
        <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">Tambah Properti</a>
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th style="width:110px">Sampul</th>
              <th>Properti</th>
              <th style="width:120px">Jenis</th>
              <th style="width:170px">Harga</th>
              <th style="width:70px">Foto</th>
              <th style="width:170px">Status</th>
              <th style="width:120px" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($properties as $property)
              <tr>
                <td>
                  <img src="{{ upload_url($property->cover_image, asset('img/placeholder.svg')) }}"
                       alt="{{ $property->title }}" width="88" height="54"
                       style="object-fit:cover;border-radius:6px" />
                </td>
                <td>
                  <span class="fw-medium d-block">{{ $property->title }}</span>
                  <small class="text-body-secondary">
                    {{ collect([
                        $property->location,
                        $property->bedrooms ? $property->bedrooms . ' KT' : null,
                        $property->bathrooms ? $property->bathrooms . ' KM' : null,
                        $property->building_area ? 'LB ' . $property->building_area . ' m²' : null,
                    ])->filter()->implode(' • ') ?: '—' }}
                  </small>
                </td>
                <td>{{ $property->type ?: '—' }}</td>
                <td>
                  @if ($property->price !== null)
                    <span class="fw-medium d-block">{{ format_rupiah($property->price) }}</span>
                    @if ($property->price_note)
                      <small class="text-body-secondary">{{ $property->price_note }}</small>
                    @endif
                  @else
                    <span class="text-body-secondary">Atas permintaan</span>
                  @endif
                </td>
                <td><span class="badge bg-label-info">{{ $property->images_count }}</span></td>
                <td>
                  @php
                      $warnaStatus = match ($property->listing_status) {
                          'dijual'    => 'primary',
                          'disewakan' => 'info',
                          default     => 'secondary',
                      };
                  @endphp
                  <span class="badge bg-label-{{ $warnaStatus }}">{{ ucfirst($property->listing_status) }}</span>
                  @unless ($property->is_active)
                    <span class="badge bg-label-secondary">Tersembunyi</span>
                  @endunless
                  @if ($property->is_featured)
                    <span class="badge bg-label-warning">Unggulan</span>
                  @endif
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.properties.edit', $property) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                    <i class="icon-base ti tabler-edit"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-icon btn-text-danger" title="Hapus"
                            data-confirm-delete="Properti &quot;{{ $property->title }}&quot; beserta seluruh fotonya akan dihapus permanen.">
                      <i class="icon-base ti tabler-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if ($properties->hasPages())
        <div class="card-body pt-3">{{ $properties->links() }}</div>
      @endif
    @endif
  </div>
@endsection
