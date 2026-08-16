<script>
  /**
   * Galeri berhalaman + modal yang bisa digeser.
   *
   * Dipakai halaman detail proyek maupun detail properti.
   *
   * Paginasi dikerjakan di sisi klien supaya perpindahan halaman terasa
   * seketika dan posisi baca tidak hilang. Modal tetap menelusuri SELURUH
   * foto — bukan hanya halaman yang sedang tampil — dan grid ikut berpindah
   * halaman mengikuti foto yang sedang dibuka.
   */
  (function () {
    const grid = document.getElementById('project-gallery');
    const lightbox = document.getElementById('lightbox');
    if (!grid || !lightbox) return;

    const figures = Array.from(grid.querySelectorAll('figure'));
    const perPage = parseInt(grid.dataset.perPage, 10) || 9;
    const pager = document.getElementById('gallery-pager');
    const totalPages = Math.ceil(figures.length / perPage);

    const gambar  = document.getElementById('lb-image');
    const caption = document.getElementById('lb-caption');
    const counter = document.getElementById('lb-counter');

    const TEKS = {
      prev:    @json(__('site.projects.prev')),
      next:    @json(__('site.projects.next')),
      page:    @json(__('site.projects.page', ['number' => ':n'])),
      counter: @json(__('site.projects.counter', ['current' => ':c', 'total' => ':t'])),
    };

    let halaman = 1;
    let aktif = 0;

    /* ---------------- Paginasi grid ---------------- */

    function tampilkanHalaman(n) {
      halaman = Math.min(Math.max(n, 1), totalPages);
      const awal = (halaman - 1) * perPage;

      figures.forEach(function (fig, i) {
        const tampil = i >= awal && i < awal + perPage;
        fig.hidden = !tampil;
        // Elemen yang baru muncul ikut dianimasikan seperti saat pertama dimuat.
        if (tampil) fig.classList.add('visible');
      });

      gambarPager();
    }

    function tombolPager(label, aria, aktifkan, onClick, nonaktif) {
      const b = document.createElement('button');
      b.type = 'button';
      b.textContent = label;
      if (aria) b.setAttribute('aria-label', aria);
      if (aktifkan) b.classList.add('active');
      if (nonaktif) b.disabled = true;
      b.addEventListener('click', onClick);
      return b;
    }

    function gambarPager() {
      if (!pager || totalPages < 2) return;
      pager.innerHTML = '';

      pager.appendChild(tombolPager('‹', TEKS.prev, false, function () {
        tampilkanHalaman(halaman - 1);
        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, halaman === 1));

      for (let i = 1; i <= totalPages; i++) {
        pager.appendChild(tombolPager(
          String(i),
          TEKS.page.replace(':n', i),
          i === halaman,
          function () {
            tampilkanHalaman(i);
            grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        ));
      }

      pager.appendChild(tombolPager('›', TEKS.next, false, function () {
        tampilkanHalaman(halaman + 1);
        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, halaman === totalPages));
    }

    /* ---------------- Modal galeri ---------------- */

    function tampilkanFoto(i) {
      aktif = (i + figures.length) % figures.length;
      const fig = figures[aktif];

      gambar.src = fig.dataset.full;
      gambar.alt = fig.querySelector('img').alt;

      const teks = fig.dataset.caption || '';
      caption.textContent = teks;
      caption.hidden = teks === '';

      counter.textContent = TEKS.counter
        .replace(':c', aktif + 1)
        .replace(':t', figures.length);

      // Grid ikut pindah halaman agar posisi tetap nyambung saat modal ditutup.
      const halamanFoto = Math.floor(aktif / perPage) + 1;
      if (halamanFoto !== halaman) tampilkanHalaman(halamanFoto);
    }

    function buka(i) {
      tampilkanFoto(i);
      lightbox.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function tutup() {
      lightbox.classList.remove('open');
      document.body.style.overflow = '';
    }

    grid.addEventListener('click', function (e) {
      const fig = e.target.closest('figure');
      if (fig) buka(parseInt(fig.dataset.index, 10));
    });

    lightbox.addEventListener('click', function (e) {
      const aksi = e.target.closest('[data-lb]');

      if (aksi) {
        const jenis = aksi.dataset.lb;
        if (jenis === 'close') tutup();
        if (jenis === 'prev') tampilkanFoto(aktif - 1);
        if (jenis === 'next') tampilkanFoto(aktif + 1);
        return;
      }

      // Klik pada area kosong di luar foto menutup modal.
      if (!e.target.closest('.lb-stage')) tutup();
    });

    document.addEventListener('keydown', function (e) {
      if (!lightbox.classList.contains('open')) return;
      if (e.key === 'Escape') tutup();
      if (e.key === 'ArrowLeft') tampilkanFoto(aktif - 1);
      if (e.key === 'ArrowRight') tampilkanFoto(aktif + 1);
    });

    // Sapuan jari di layar sentuh.
    let sentuhX = null;
    lightbox.addEventListener('touchstart', function (e) {
      sentuhX = e.changedTouches[0].clientX;
    }, { passive: true });

    lightbox.addEventListener('touchend', function (e) {
      if (sentuhX === null) return;
      const jarak = e.changedTouches[0].clientX - sentuhX;
      if (Math.abs(jarak) > 45) tampilkanFoto(aktif + (jarak < 0 ? 1 : -1));
      sentuhX = null;
    }, { passive: true });

    tampilkanHalaman(1);
  })();
</script>
