<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Menangani upload gambar ke disk "public".
 *
 * Gambar yang terlalu besar otomatis diperkecil memakai ekstensi GD supaya
 * halaman depan tetap ringan. Kalau GD tidak aktif, file disimpan apa adanya
 * sehingga upload tetap berhasil.
 */
class ImageService
{
    /** Lebar maksimum (px) hasil simpan. */
    public const MAX_WIDTH = 1920;

    /** Kualitas JPEG/WEBP hasil kompresi. */
    public const QUALITY = 82;

    /**
     * Simpan file upload, kembalikan path relatif terhadap disk "public".
     * Bila $oldPath diisi, file lama dihapus setelah file baru tersimpan.
     */
    public function store(UploadedFile $file, string $folder, ?string $oldPath = null): string
    {
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $name = Str::limit($name ?: 'image', 60, '');
        $filename = $name.'-'.Str::random(8).'.'.strtolower($file->getClientOriginalExtension());
        $path = trim($folder, '/').'/'.$filename;

        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        $this->downscale(Storage::disk('public')->path($path));

        if ($oldPath) {
            $this->delete($oldPath);
        }

        return $path;
    }

    /** Hapus file dari disk "public" bila ada. */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Perkecil gambar di tempat bila lebarnya melebihi MAX_WIDTH.
     * Tidak melempar error kalau GD tidak tersedia atau file bukan gambar
     * yang didukung — upload tetap dianggap berhasil.
     */
    protected function downscale(string $absolutePath): void
    {
        if (! function_exists('imagecreatetruecolor')) {
            return;
        }

        $info = @getimagesize($absolutePath);
        if (! $info) {
            return;
        }

        [$width, $height] = $info;
        if ($width <= self::MAX_WIDTH) {
            return;
        }

        $source = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($absolutePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($absolutePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($absolutePath),
            default        => null,
        };

        if (! $source) {
            return;
        }

        $newWidth  = self::MAX_WIDTH;
        $newHeight = (int) round($height * ($newWidth / $width));
        $canvas    = imagecreatetruecolor($newWidth, $newHeight);

        // Pertahankan transparansi untuk PNG & WEBP.
        if (in_array($info[2], [IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
        }

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        match ($info[2]) {
            IMAGETYPE_JPEG => imagejpeg($canvas, $absolutePath, self::QUALITY),
            IMAGETYPE_PNG  => imagepng($canvas, $absolutePath, 6),
            IMAGETYPE_WEBP => imagewebp($canvas, $absolutePath, self::QUALITY),
        };

        imagedestroy($canvas);
        imagedestroy($source);
    }
}
