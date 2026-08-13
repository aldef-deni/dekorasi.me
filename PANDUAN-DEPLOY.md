# Dekorasi.me — Panduan Deploy ke cPanel

Website company profile desain interior + dashboard administrator berbasis
**Laravel 11** dengan UI admin **Vuexy v10.11.1**.

> **Struktur project ini diratakan (flat): TIDAK ADA folder `public`.**
> Isi folder `public` sudah dipindahkan ke root, sehingga URL tidak pernah
> mengandung `/public`. Ekstrak langsung ke `public_html` (atau folder domain).

---

## 1. Ekstrak file

Ekstrak isi ZIP ini ke **folder domain** di cPanel — yaitu folder yang menjadi
Document Root, misalnya `public_html` atau `public_html/dev`.

Setelah diekstrak, di folder tersebut harus ada:

```
index.php      .htaccess     artisan       composer.json
app/           bootstrap/    config/       database/
resources/     routes/       storage/      vendor/
assets/        css/          img/          favicon.ico    robots.txt
```

> Folder `vendor/` **tidak** disertakan dalam ZIP karena tidak berubah. Jangan dihapus.

**Hapus folder `public/` lama** kalau masih tersisa dari versi sebelumnya.

---

## 2. Siapkan file `.env`

Salin `.env.example` menjadi `.env`, lalu isi bagian database sesuai
cPanel &rsaquo; MySQL Databases:

```
APP_NAME="Dekorasi.me"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dev.dekorasi.me

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=dekn3383_larav85
DB_USERNAME=dekn3383_xxxxx
DB_PASSWORD=kata-sandi-database
```

`APP_URL` harus benar — nilai inilah yang dipakai untuk membentuk URL gambar
yang diunggah lewat dashboard.

Lalu buat application key:

```bash
php artisan key:generate
```

---

## 3. Jalankan migrasi & konten awal

**Ini langkah yang menyebabkan error "Table … sliders doesn't exist".**

```bash
php artisan migrate --force
php artisan db:seed --force
```

Cek hasilnya:

```bash
php artisan migrate:status
```

Semua migrasi harus berstatus `Ran`, termasuk `2026_08_12_000001` sampai
`2026_08_12_000006` dan `2026_08_14_000001`. Kalau migrasi tersebut tidak muncul, berarti folder
`database/migrations/` belum ikut terunggah.

Seeder mengisi: akun admin, pengaturan situs, 2 slide, 3 paket layanan
(Silver / Gold / Platinum), dan 6 contoh proyek. Seeder aman dijalankan ulang —
data yang sudah Anda ubah tidak tertimpa.

### Memperbarui server yang sudah berjalan

Kalau database di server sudah terisi versi sebelumnya (masih berupa "Layanan",
belum "Paket Layanan"), jalankan dua perintah ini:

```bash
php artisan migrate --force
php artisan db:seed --class=PaketLayananSeeder --force
```

Perintah pertama menambah kolom paket (keterangan, isi paket, harga, penanda
unggulan). Perintah kedua mengisi tiga paket Silver, Gold, dan Platinum.

> Aman diulang: paket dicocokkan lewat slug, jadi diperbarui — bukan
> digandakan. Layanan lama di luar ketiga paket itu **dinonaktifkan, bukan
> dihapus**, sehingga datanya tetap ada dan bisa diaktifkan lagi dari dashboard.

### Akun admin bawaan

| Email | Kata sandi |
|---|---|
| `admin@dekorasi.me` | `dekorasi2026` |

> **Segera ganti kata sandi ini setelah login pertama:**
> `php artisan tinker` &rarr;
> `App\Models\User::first()->update(['password' => Hash::make('sandi-baru')]);`

---

## 4. Folder `uploads`

**`php artisan storage:link` TIDAK diperlukan — jangan dijalankan.**

Hosting ini mematikan `symlink()` dan `exec()` lewat `disable_functions`,
sehingga perintah itu pasti gagal dengan pesan
*"Call to undefined function Illuminate\Filesystem\exec()"*.

Karena itu gambar unggahan disimpan **langsung** di folder `uploads/` pada root
project (diatur di `config/filesystems.php`), bukan di `storage/app/public`
yang butuh symlink. Apache menyajikannya langsung — lebih cepat daripada
melewatkan setiap gambar ke PHP.

Yang perlu dipastikan hanya izin tulisnya:

```bash
chmod -R 775 uploads
```

Folder `uploads/` sudah berisi `.htaccess` yang mematikan eksekusi skrip dan
menolak berkas non-gambar. **Jangan dihapus.**

### Pindah dari versi sebelumnya

Kalau sebelumnya sudah ada gambar terunggah di `storage/app/public`, pindahkan:

```bash
mv storage/app/public/* uploads/
```

---

## 5. Optimasi produksi

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jalankan ulang ketiganya setiap kali `.env` atau kode diubah.
Kalau ada perubahan tak muncul, jalankan `php artisan optimize:clear` dulu.

---

## 6. Izin folder

```bash
chmod -R 775 storage bootstrap/cache
```

---

## Gambar tidak tampil? Jalankan diagnosa

```bash
php artisan dekorasi:diagnosa
```

Perintah ini memeriksa dan melaporkan:

1. `APP_URL` — kalau masih `localhost`, semua URL gambar akan salah
2. Folder `uploads` — ada, bisa ditulis, dan `.htaccess` pelindungnya masih ada
3. Berkas statis wajib (`img/placeholder.svg`, `css/site.css`, aset Vuexy, dst.)
4. URL contoh yang bisa Anda buka langsung di browser
5. Status cache config & route
6. **Uji keamanan** — memastikan `.env` dan berkas sensitif lain tidak bisa diakses publik

Cara membaca hasil uji URL di browser:

| Kode | Artinya |
|---|---|
| 200 | Berkas tampil — normal |
| 404 | Berkas tidak ada di server (upload ulang berkasnya) |
| 403 | Diblokir `.htaccess` |

> Gambar unggahan tetap punya **jaring pengaman**: bila karena suatu hal
> Apache tidak bisa menyajikan berkas di `uploads/`, Laravel melayaninya lewat
> rute `/uploads/{path}`. Rute itu hanya menyajikan berkas gambar di dalam
> folder unggahan dan menolak upaya keluar folder (`../`).

---

## Keamanan struktur flat — penting

Karena tidak ada folder `public`, folder aplikasi (`app/`, `config/`,
`vendor/`, `.env`) ikut berada di dalam Document Root. File `.htaccess` di root
sudah memblokir semuanya, tapi **proteksi ini bergantung pada Apache**.

Yang perlu dipastikan:

1. File `.htaccess` benar-benar ikut terunggah (file diawali titik sering
   tersembunyi di File Manager — aktifkan "Show Hidden Files").
2. `mod_rewrite` aktif di hosting.
3. Uji manual setelah deploy — semuanya harus **403 Forbidden** atau **404**
   (`php artisan dekorasi:diagnosa` menguji ini otomatis):
   - `https://domain-anda/.env`
   - `https://domain-anda/composer.json`
   - `https://domain-anda/app/Models/User.php`
   - `https://domain-anda/storage/logs/laravel.log`
4. `APP_DEBUG=false` di produksi, supaya detail error tidak tampil ke publik.

> **Kalau `.env` ternyata bisa dibaca:** isinya memuat `APP_KEY` dan kata sandi
> database. Setelah menutup celahnya, ganti kata sandi database di cPanel,
> perbarui `.env`, lalu jalankan `php artisan key:generate`.
>
> Ini berlaku juga untuk subdomain pengembangan — `dev.` tetap terbuka di
> internet dan biasanya memakai kredensial database yang sama.

### Khusus subdomain pengembangan

Agar situs dev tidak terindeks mesin pencari, isi `robots.txt` dengan:

```
User-agent: *
Disallow: /
```

Kembalikan ke isi semula saat pindah ke domain produksi.

> **Alternatif yang lebih aman:** arahkan Document Root domain ke subfolder
> `public` lewat cPanel &rsaquo; Domains &rsaquo; Manage. Dengan begitu folder
> aplikasi berada di luar jangkauan web sepenuhnya. Struktur flat ini dipakai
> atas permintaan, dan aman selama tiga poin di atas terpenuhi.

---

## Struktur yang perlu diketahui

| Lokasi | Isi |
|---|---|
| `index.php` | Front controller (path sudah disesuaikan ke struktur flat) |
| `.htaccess` | Rewrite Laravel + proteksi folder aplikasi + cache aset |
| `assets/` | Aset Vuexy (CSS/JS sudah ter-compile — tanpa `npm install`) |
| `css/site.css` | Gaya halaman depan (tema terang, aksen emas) |
| `css/admin-brand.css` | Penyesuaian warna Vuexy ke merek Dekorasi.me |
| `img/brand/` | Logo Dekorasi.me |
| `uploads/` | Gambar yang diunggah dari dashboard (tanpa symlink) |
| `resources/views/site/` | Halaman depan (company profile) |
| `resources/views/admin/` | Halaman dashboard administrator |

## Alamat penting

| Halaman | URL |
|---|---|
| Website | `/` |
| Login admin | `/admin/login` |
| Dashboard | `/admin` |

## Modul yang bisa diatur dari dashboard

- **Slider Beranda** — gambar & teks besar di bagian atas halaman depan
- **Paket Layanan** — Silver/Gold/Platinum: nama, keterangan, isi paket (satu poin per baris), harga, ikon, penanda unggulan
- **Proyek** — portofolio + galeri multi-foto (urutan bisa digeser drag & drop)
- **Tentang Kami** — profil, visi, misi, 4 angka pencapaian, 2 gambar
- **Pengaturan Situs** — logo, favicon, kontak, WhatsApp, sosial media, SEO

---

## Catatan teknis

- Gambar yang diunggah otomatis diperkecil ke lebar maksimal **1920 px** memakai
  ekstensi GD. Jika GD tidak aktif di server, file tetap tersimpan apa adanya.
- Nomor WhatsApp boleh diisi format `08…` atau `62…` — otomatis dikonversi ke
  tautan `wa.me`.
- Kolom **Kode Embed Google Maps** diisi **URL saja** (bagian `src="…"` dari
  kode embed), bukan seluruh tag `<iframe>`.
- Tidak ada proses build front-end. Tidak perlu Node.js maupun `npm run build`.
