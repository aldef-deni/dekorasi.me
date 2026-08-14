<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectImageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Cadangan penyaji berkas unggahan
|--------------------------------------------------------------------------
|
| Normalnya /uploads/... dilayani langsung oleh Apache lewat symlink yang
| dibuat "php artisan storage:link", sehingga rute ini tidak pernah tersentuh.
| Bila hosting melarang symlink (atau symlink-nya belum dibuat), rute ini
| menjadi jaring pengaman agar gambar tetap tampil.
|
| Sengaja didaftarkan eksplisit di sini — bukan lewat opsi 'serve' pada disk —
| karena opsi tersebut dilewati Laravel begitu route:cache dijalankan.
|
*/
Route::get('uploads/{path}', function (string $path) {
    // Tolak upaya keluar dari folder unggahan (../, ..\, atau versi ter-encode).
    $normalized = str_replace('\\', '/', urldecode($path));

    abort_if(str_contains($normalized, '..') || str_starts_with($normalized, '/'), 404);

    // Hanya berkas gambar yang boleh disajikan lewat rute ini.
    $ekstensi = strtolower(pathinfo($normalized, PATHINFO_EXTENSION));

    abort_unless(in_array($ekstensi, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'ico'], true), 404);

    $disk = Storage::disk('public');

    abort_unless($disk->exists($normalized), 404);

    return $disk->response($normalized, null, [
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('uploads.show');

/*
|--------------------------------------------------------------------------
| Halaman Publik (Company Profile)
|--------------------------------------------------------------------------
*/
Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/tentang-kami', 'about')->name('about');
    Route::get('/layanan', 'services')->name('services.index');
    Route::get('/layanan/{service:slug}', 'serviceDetail')->name('services.show');
    Route::get('/proyek', 'projects')->name('projects.index');
    Route::get('/proyek/{project:slug}', 'projectDetail')->name('projects.show');
    Route::get('/kontak', 'contact')->name('contact');
});

/*
|--------------------------------------------------------------------------
| Autentikasi Admin
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/admin/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Administrator
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('sliders', SliderController::class)->except('show');
    Route::resource('services', ServiceController::class)->except('show');
    Route::resource('projects', ProjectController::class)->except('show');

    // Galeri foto proyek
    Route::post('projects/{project}/images', [ProjectImageController::class, 'store'])->name('projects.images.store');
    Route::delete('project-images/{image}', [ProjectImageController::class, 'destroy'])->name('projects.images.destroy');
    Route::post('projects/{project}/images/reorder', [ProjectImageController::class, 'reorder'])->name('projects.images.reorder');

    // Profil administrator
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Halaman Tentang Kami
    Route::get('about', [AboutController::class, 'edit'])->name('about.edit');
    Route::put('about', [AboutController::class, 'update'])->name('about.update');

    // Pengaturan Situs
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
