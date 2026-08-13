@if (session('success'))
  <div class="alert alert-success alert-dismissible d-flex align-items-center" role="alert">
    <i class="icon-base ti tabler-circle-check me-2"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger alert-dismissible d-flex align-items-center" role="alert">
    <i class="icon-base ti tabler-alert-circle me-2"></i>
    <div>{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible" role="alert">
    <h6 class="alert-heading mb-2 d-flex align-items-center">
      <i class="icon-base ti tabler-alert-triangle me-2"></i> Periksa kembali isian berikut
    </h6>
    <ul class="mb-0 ps-3">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
@endif
