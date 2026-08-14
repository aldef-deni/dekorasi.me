<?php

/*
|--------------------------------------------------------------------------
| Pesan Validasi Bahasa Indonesia
|--------------------------------------------------------------------------
|
| Laravel tidak menyertakan terjemahan bawaan, sehingga tanpa berkas ini
| pesan kesalahan tampil bahasa Inggris bercampur label Indonesia, mis.
| "The kata sandi baru field must be at least 8 characters."
|
| Daftar di bawah mencakup aturan yang benar-benar dipakai aplikasi ini.
|
*/

return [
    'accepted'         => ':attribute wajib disetujui.',
    'active_url'       => ':attribute bukan URL yang valid.',
    'after'            => ':attribute harus tanggal setelah :date.',
    'array'            => ':attribute harus berupa daftar.',
    'before'           => ':attribute harus tanggal sebelum :date.',
    'boolean'          => ':attribute harus bernilai ya atau tidak.',
    'confirmed'        => 'Konfirmasi :attribute tidak sama.',
    'current_password' => ':attribute tidak cocok.',
    'date'             => ':attribute bukan tanggal yang valid.',
    'different'        => ':attribute dan :other harus berbeda.',
    'digits'           => ':attribute harus terdiri dari :digits angka.',
    'email'            => 'Format :attribute tidak valid.',
    'exists'           => ':attribute yang dipilih tidak valid.',
    'file'             => ':attribute harus berupa berkas.',
    'filled'           => ':attribute wajib diisi.',
    'image'            => ':attribute harus berupa gambar.',
    'in'               => ':attribute yang dipilih tidak valid.',
    'integer'          => ':attribute harus berupa angka bulat.',
    'mimes'            => ':attribute harus berformat: :values.',
    'numeric'          => ':attribute harus berupa angka.',
    'present'          => ':attribute wajib ada.',
    'regex'            => 'Format :attribute tidak valid.',
    'required'         => ':attribute wajib diisi.',
    'same'             => ':attribute dan :other harus sama.',
    'string'           => ':attribute harus berupa teks.',
    'unique'           => ':attribute sudah digunakan.',
    'uploaded'         => ':attribute gagal diunggah. Periksa ukuran berkas.',
    'url'              => 'Format :attribute tidak valid.',

    'min' => [
        'array'   => ':attribute harus berisi minimal :min item.',
        'file'    => 'Ukuran :attribute minimal :min kilobyte.',
        'numeric' => ':attribute minimal :min.',
        'string'  => ':attribute harus minimal :min karakter.',
    ],

    'max' => [
        'array'   => ':attribute maksimal berisi :max item.',
        'file'    => 'Ukuran :attribute maksimal :max kilobyte.',
        'numeric' => ':attribute maksimal :max.',
        'string'  => ':attribute maksimal :max karakter.',
    ],

    'between' => [
        'array'   => ':attribute harus berisi antara :min sampai :max item.',
        'file'    => 'Ukuran :attribute harus antara :min sampai :max kilobyte.',
        'numeric' => ':attribute harus antara :min sampai :max.',
        'string'  => ':attribute harus antara :min sampai :max karakter.',
    ],

    'password' => [
        'letters'       => ':attribute harus mengandung minimal satu huruf.',
        'mixed'         => ':attribute harus mengandung huruf besar dan huruf kecil.',
        'numbers'       => ':attribute harus mengandung minimal satu angka.',
        'symbols'       => ':attribute harus mengandung minimal satu simbol.',
        'uncompromised' => ':attribute pernah bocor dalam kebocoran data. Silakan pilih yang lain.',
    ],

    /*
    | Nama atribut umum. Sebagian besar controller sudah mengirim nama
    | khusus lewat argumen ketiga validate(), daftar ini menjadi cadangan.
    */
    'attributes' => [
        'name'                  => 'nama',
        'email'                 => 'email',
        'password'              => 'kata sandi',
        'password_confirmation' => 'konfirmasi kata sandi',
        'current_password'      => 'kata sandi saat ini',
        'avatar'                => 'foto profil',
        'title'                 => 'judul',
        'subtitle'              => 'keterangan',
        'slug'                  => 'slug',
        'excerpt'               => 'ringkasan',
        'description'           => 'deskripsi',
        'features'              => 'isi paket',
        'price'                 => 'harga',
        'image'                 => 'gambar',
        'images'                => 'foto',
        'cover_image'           => 'gambar sampul',
        'icon'                  => 'ikon',
        'category'              => 'kategori',
        'client'                => 'klien',
        'location'              => 'lokasi',
        'area'                  => 'luas',
        'year'                  => 'tahun',
        'sort_order'            => 'urutan',
        'cta_label'             => 'label tombol',
        'cta_url'               => 'tautan tombol',
    ],
];
