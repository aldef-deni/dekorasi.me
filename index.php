<?php

/**
 * Front controller Laravel.
 *
 * Struktur project ini sengaja diratakan: isi folder "public" dipindahkan ke
 * root sehingga URL tidak mengandung "/public". Karena itu seluruh path di
 * bawah ini memakai __DIR__ (root project), bukan __DIR__.'/..'.
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Cek mode maintenance...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Daftarkan autoloader Composer...
require __DIR__.'/vendor/autoload.php';

// Jalankan Laravel dan tangani request...
(require_once __DIR__.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
