<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Property;
use App\Models\Video;
use App\Support\ImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Pengelola video untuk Proyek dan Properti.
 *
 * Satu controller melayani keduanya lewat parameter {jenis}, sehingga aturan
 * unggah dan pembacaan tautan tidak perlu ditulis dua kali.
 */
class VideoController extends Controller
{
    /** Modul yang boleh punya video. Sekaligus daftar putih parameter {jenis}. */
    private const PEMILIK = [
        'projects'   => Project::class,
        'properties' => Property::class,
    ];

    public function __construct(private readonly ImageService $images)
    {
    }

    public function store(Request $request, string $jenis, string $id): RedirectResponse
    {
        $pemilik = $this->pemilik($jenis, $id);

        $data = $request->validate([
            'source' => ['required', Rule::in(Video::SOURCES)],
            'title'  => ['nullable', 'string', 'max:150'],
            'title_en' => ['nullable', 'string', 'max:150'],
            'file'   => [
                Rule::requiredIf($request->input('source') === 'upload'),
                'nullable', 'file',
                'mimes:'.implode(',', Video::MIMES),
                // Batas mengikuti setelan server, bukan angka tetap di kode.
                'max:'.batas_unggah_kb(),
            ],
            'url'    => [Rule::requiredIf($request->input('source') !== 'upload'), 'nullable', 'string', 'max:500'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'file.max'   => 'Ukuran video melebihi batas server ('.ukuran_terbaca(batas_unggah_kb()).'). '
                            .'Perbesar batas unggah di cPanel, atau tempelkan tautan YouTube/Vimeo.',
            'file.mimes' => 'Format video harus '.strtoupper(implode(', ', Video::MIMES)).'.',
        ], [
            'file'   => 'berkas video',
            'url'    => 'tautan video',
            'title'  => 'judul',
            'poster' => 'gambar sampul',
        ]);

        $video = new Video([
            'sort_order' => (int) $pemilik->videos()->max('sort_order') + 1,
            'title'      => $data['title'] ?? null,
        ]);

        if ($data['source'] === 'upload') {
            $video->source = 'upload';
            $video->path = $this->simpanVideo($request->file('file'), $jenis, $pemilik->getKey());
        } else {
            $terbaca = Video::bacaTautan($data['url']);

            if (! $terbaca) {
                return back()
                    ->withInput()
                    ->withErrors(['url' => 'Tautan tidak dikenali. Tempelkan alamat video YouTube atau Vimeo.']);
            }

            [$video->source, $video->video_id] = $terbaca;
            $video->url = $data['url'];
        }

        if ($request->hasFile('poster')) {
            $video->poster = $this->images->store($request->file('poster'), 'videos/poster');
        }

        $video->setTranslation('en', ['title' => $data['title_en'] ?? null]);

        $pemilik->videos()->save($video);

        return back()->with('success', 'Video berhasil ditambahkan.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $this->hapusBerkas($video);
        $video->delete();

        return back()->with('success', 'Video berhasil dihapus.');
    }

    public function reorder(Request $request, string $jenis, string $id): JsonResponse
    {
        $pemilik = $this->pemilik($jenis, $id);

        $validated = $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($validated['order'] as $posisi => $videoId) {
            $pemilik->videos()->whereKey($videoId)->update(['sort_order' => $posisi]);
        }

        return response()->json(['status' => 'ok']);
    }

    /** Temukan Proyek / Properti pemilik video, atau 404 bila jenisnya tidak dikenal. */
    private function pemilik(string $jenis, string $id): Model
    {
        abort_unless(isset(self::PEMILIK[$jenis]), 404);

        $kelas = self::PEMILIK[$jenis];

        return $kelas::where('slug', $id)->orWhere('id', $id)->firstOrFail();
    }

    /**
     * Simpan berkas video apa adanya — tanpa pengecilan, karena mengolah video
     * butuh ffmpeg yang tidak tersedia di hosting berbagi.
     */
    private function simpanVideo(\Illuminate\Http\UploadedFile $file, string $jenis, int|string $id): string
    {
        $nama = \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $nama = \Illuminate\Support\Str::limit($nama ?: 'video', 60, '');
        $berkas = $nama.'-'.\Illuminate\Support\Str::random(8).'.'.strtolower($file->getClientOriginalExtension());
        $path = "videos/{$jenis}/{$id}/{$berkas}";

        Storage::disk('public')->putFileAs(dirname($path), $file, basename($path));

        return $path;
    }

    /** Hapus berkas video dan posternya bila ada. */
    private function hapusBerkas(Video $video): void
    {
        if ($video->path && Storage::disk('public')->exists($video->path)) {
            Storage::disk('public')->delete($video->path);
        }

        $this->images->delete($video->poster);
    }
}
