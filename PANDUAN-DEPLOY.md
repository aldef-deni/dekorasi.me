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
`2026_08_12_000006`. Kalau migrasi tersebut tidak muncul, berarti folder
`database/migrations/` belum ikut terunggah.

Seeder mengisi: akun admin, pengaturan situs, 2 slide, 6 layanan, dan 6 contoh
proyek. Seeder aman dijalankan ulang — data yang sudah Anda ubah tidak tertimpa.

### Akun admin bawaan

| Email | Kata sandi |
|---|---|
| `admin@dekorasi.me` | `dekorasi2026` |

> **Segera ganti kata sandi ini setelah login pertama:**
> `php artisan tinker` &rarr;
> `App\Models\User::first()->update(['password' => Hash::make('sandi-baru')]);`

---

## 4. Symlink `uploads` (wajib, agar gambar tampil)

```bash
php artisan storage:link
```

Perintah ini membuat symlink **`uploads`** &rarr; `storage/app/public`.

> Namanya `uploads`, **bukan** `storage`, karena struktur flat membuat nama
> `storage` sudah dipakai folder framework. Pengaturan ini ada di
> `config/filesystems.php`.

Kalau hosting melarang symlink, buat manual:

```bash
ln -s storage/app/public uploads
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

## Keamanan struktur flat — penting

Karena tidak ada folder `public`, folder aplikasi (`app/`, `config/`,
`vendor/`, `.env`) ikut berada di dalam Document Root. File `.htaccess` di root
sudah memblokir semuanya, tapi **proteksi ini bergantung pada Apache**.

Yang perlu dipastikan:

1. File `.htaccess` benar-benar ikut terunggah (file diawali titik sering
   tersembunyi di File Manager — aktifkan "Show Hidden Files").
2. `mod_rewrite` aktif di hosting.
3. Uji manual setelah deploy — semuanya harus **403 Forbidden** atau **404**:
   - `https://domain-anda/.env`
   - `https://domain-anda/composer.json`
   - `https://domain-anda/app/Models/User.php`
   - `https://domain-anda/storage/logs/laravel.log`
4. `APP_DEBUG=false` di produksi, supaya detail error tidak tampil ke publik.

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
| `css/site.css` | Gaya halaman depan (gelap-emas) |
| `css/admin-brand.css` | Penyesuaian warna Vuexy ke merek Dekorasi.me |
| `img/brand/` | Logo Dekorasi.me |
| `uploads/` | Symlink ke gambar yang diunggah dari dashboard |
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
- **Layanan** — daftar jasa, ikon, ringkasan, deskripsi lengkap
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
