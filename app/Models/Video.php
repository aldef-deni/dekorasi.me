<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Video milik Proyek atau Properti.
 *
 * Dua sumber didukung:
 *   - upload  : berkas video yang diunggah admin, disajikan sendiri oleh server.
 *   - youtube / vimeo : video yang sudah ada di layanan luar, cukup ditempel
 *     tautannya. Ini penting karena hosting berbagi membatasi ukuran unggahan
 *     (umumnya 64 MB), sementara video walkthrough gampang melebihi itu.
 */
class Video extends Model
{
    use HasTranslations;

    /** Sumber video yang dikenali sistem. */
    public const SOURCES = ['upload', 'youtube', 'vimeo'];

    /**
     * Format berkas video yang boleh diunggah.
     *
     * Sengaja MP4 saja: itu satu-satunya format yang bisa diputar semua
     * peramban dan ponsel. Format lain seperti AVI dan MPEG memang bisa
     * disimpan, tetapi tidak akan pernah bisa ditonton langsung di halaman,
     * jadi menerimanya hanya menimbulkan kebingungan.
     */
    public const MIMES = ['mp4'];

    protected array $translatable = ['title'];

    protected $fillable = [
        'videoable_type', 'videoable_id',
        'title', 'source', 'path', 'url', 'video_id', 'poster',
        'translations', 'sort_order',
    ];

    protected $casts = [
        'translations' => 'array',
        'sort_order'   => 'integer',
    ];

    public function videoable(): MorphTo
    {
        return $this->morphTo();
    }

    public function diunggah(): bool
    {
        return $this->source === 'upload';
    }

    /** URL berkas video untuk tag <video>. */
    public function fileUrl(): ?string
    {
        return $this->diunggah() ? upload_url($this->path) : null;
    }

    /**
     * URL sematan untuk iframe.
     *
     * Parameter tambahan dipilih agar pemutar tidak menampilkan video usulan
     * dari kanal lain setelah selesai — halaman tetap terasa milik sendiri.
     */
    public function embedUrl(): ?string
    {
        return match ($this->source) {
            'youtube' => 'https://www.youtube-nocookie.com/embed/'.$this->video_id.'?rel=0&modestbranding=1&autoplay=1',
            'vimeo'   => 'https://player.vimeo.com/video/'.$this->video_id.'?autoplay=1&title=0&byline=0',
            default   => null,
        };
    }

    /**
     * Gambar sampul. Bila admin tidak mengunggah poster, YouTube masih bisa
     * menyediakannya dari id video; selain itu dikembalikan null agar pemanggil
     * memakai tampilan cadangan.
     */
    public function posterUrl(): ?string
    {
        if ($this->poster) {
            return upload_url($this->poster);
        }

        if ($this->source === 'youtube' && $this->video_id) {
            return 'https://img.youtube.com/vi/'.$this->video_id.'/hqdefault.jpg';
        }

        return null;
    }

    /** Judul siap tampil; memakai penomoran bila admin tidak mengisinya. */
    public function judul(int $nomor = 1): string
    {
        return (string) ($this->t('title') ?: __('site.videos.default_title', ['number' => $nomor]));
    }

    /**
     * Baca tautan YouTube / Vimeo menjadi pasangan [sumber, id].
     * Mengembalikan null bila tautannya tidak dikenali.
     *
     * @return array{0: string, 1: string}|null
     */
    public static function bacaTautan(string $url): ?array
    {
        $url = trim($url);

        // youtu.be/ID · youtube.com/watch?v=ID · /embed/ID · /shorts/ID · /live/ID
        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~i', $url, $cocok)) {
            return ['youtube', $cocok[1]];
        }

        // vimeo.com/ID · player.vimeo.com/video/ID
        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~i', $url, $cocok)) {
            return ['vimeo', $cocok[1]];
        }

        return null;
    }
}
