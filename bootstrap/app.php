<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
 * Helper aplikasi: setting(), upload_url(), whatsapp_url().
 *
 * Dimuat manual di sini — bukan hanya lewat "autoload.files" di composer.json —
 * supaya tetap tersedia di hosting tanpa perlu menjalankan
 * "composer dump-autoload" setelah mengunggah file baru.
 * Fungsi di dalamnya sudah dibungkus function_exists(), jadi aman meski
 * autoloader Composer ikut memuatnya.
 */
require_once __DIR__.'/../app/Support/helpers.php';

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Bahasa tampilan ditentukan per permintaan berdasarkan pilihan di sesi.
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create()
    // Isi folder "public" dipindahkan ke root project, sehingga URL tidak
    // mengandung "/public". public_path() harus ikut menunjuk ke root.
    ->usePublicPath(dirname(__DIR__));
