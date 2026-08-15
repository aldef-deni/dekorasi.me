<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            // Berkas unggahan disimpan LANGSUNG di folder "uploads" pada root
            // project, bukan di storage/app/public + symlink.
            //
            // Alasannya: hosting cPanel ini mematikan symlink() dan exec()
            // lewat disable_functions, sehingga "php artisan storage:link"
            // mustahil dijalankan. Dengan cara ini Apache menyajikan gambar
            // secara langsung — tidak perlu symlink, dan jauh lebih ringan
            // daripada melewatkan setiap gambar ke PHP.
            //
            // Keamanan: folder uploads punya .htaccess sendiri yang mematikan
            // eksekusi skrip, dan unggahan sudah divalidasi hanya berupa gambar.
            'root' => base_path('uploads'),
            // URL sengaja ROOT-RELATIF ("/uploads"), bukan absolut dari APP_URL.
            //
            // Dengan begitu gambar tetap tampil di domain mana pun tanpa perlu
            // mengubah konfigurasi — termasuk saat pindah dari subdomain
            // pengembangan ke domain utama. Kalau memakai APP_URL, seluruh
            // gambar langsung mati begitu domain lama dihapus.
            'url' => '/uploads',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    // Sengaja kosong: berkas unggahan sudah berada langsung di folder
    // "uploads" (lihat disk 'public' di atas), jadi tidak ada symlink yang
    // perlu dibuat. Hosting ini pun mematikan symlink() lewat disable_functions,
    // sehingga "php artisan storage:link" tidak diperlukan sama sekali.
    'links' => [],

];
