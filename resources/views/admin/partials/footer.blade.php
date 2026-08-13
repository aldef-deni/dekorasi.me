<footer class="content-footer footer bg-footer-theme">
  <div class="container-xxl">
    <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
      <div class="text-body-secondary small">
        &copy; {{ date('Y') }} {{ setting('site.name', 'Dekorasi.me') }} &mdash; Panel Administrator
      </div>
      <div class="text-body-secondary small">
        Laravel {{ app()->version() }}
      </div>
    </div>
  </div>
</footer>
