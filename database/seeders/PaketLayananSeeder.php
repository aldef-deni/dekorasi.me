<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Mengisi tiga paket layanan: Silver, Gold, dan Platinum.
 *
 * Dipisah dari DatabaseSeeder agar bisa dijalankan sendiri di server yang
 * datanya sudah ada:
 *
 *   php artisan db:seed --class=PaketLayananSeeder --force
 *
 * Aman diulang: paket dicocokkan lewat slug, jadi memperbarui — bukan
 * menggandakan. Layanan lama di luar ketiga slug ini dinonaktifkan
 * (bukan dihapus) supaya datanya tidak hilang.
 */
class PaketLayananSeeder extends Seeder
{
    public function run(): void
    {
        $paket = [
            [
                'slug'     => 'paket-silver',
                'title'    => 'Paket Silver',
                'subtitle' => 'Design Only',
                'icon'     => 'tabler-pencil',
                'excerpt'  => 'Untuk Anda yang sudah punya tim pelaksana dan hanya membutuhkan rancangan matang.',
                'features' => implode("\n", [
                    'Konsultasi konsep awal.',
                    'Tata letak ruangan (Layout 2D Layout Plan).',
                    'Visualisasi desain (3D Rendering hingga 3 view).',
                    'Gambar kerja teknis (Production Drawing).',
                ]),
                'description' => implode("\n\n", [
                    'Paket Silver berfokus pada perancangan. Anda menerima dokumen desain yang lengkap dan siap dieksekusi oleh kontraktor atau tukang pilihan Anda sendiri.',
                    'Prosesnya dimulai dari konsultasi konsep: kami memetakan kebutuhan, kebiasaan penghuni, dan anggaran. Hasilnya diterjemahkan menjadi denah tata ruang, lalu divisualisasikan dalam bentuk 3D agar Anda bisa menilai proporsi, warna, dan pencahayaan sebelum biaya pengerjaan keluar.',
                    'Tahap akhir berupa gambar kerja teknis — ukuran, detail sambungan, dan spesifikasi material — sehingga pelaksana di lapangan tidak perlu menebak.',
                ]),
                'sort_order'  => 1,
                'is_featured' => false,
            ],
            [
                'slug'     => 'paket-gold',
                'title'    => 'Paket Gold',
                'subtitle' => 'Design + Custom Furniture',
                'icon'     => 'tabler-armchair',
                'excerpt'  => 'Rancangan lengkap sekaligus pembuatan furnitur custom yang presisi mengikuti ruang Anda.',
                'features' => implode("\n", [
                    'Seluruh keuntungan Paket Silver.',
                    'Pembuatan dan pemasangan furnitur kustom (kabinet, tempat tidur, lemari baju, dll).',
                    'Pemilihan material finis (HPL, Duco, Veneer).',
                    'Pengawasan instalasi produk.',
                ]),
                'description' => implode("\n\n", [
                    'Paket Gold menambahkan produksi furnitur ke dalam lingkup kerja. Furnitur pabrikan jarang pas untuk ruang dengan bentuk tidak biasa — di sini setiap unit dibuat berdasarkan pengukuran langsung di lapangan.',
                    'Anda dibantu memilih material finis yang sesuai: HPL untuk daya tahan dan efisiensi biaya, Duco untuk hasil mulus dan mewah, atau Veneer untuk tekstur kayu asli.',
                    'Pemasangan diawasi tim kami, sehingga hasil akhirnya sesuai gambar kerja dan rapi sampai ke detail sambungan.',
                ]),
                'sort_order'  => 2,
                'is_featured' => true,
            ],
            [
                'slug'     => 'paket-platinum',
                'title'    => 'Paket Platinum',
                'subtitle' => 'Full Turn-Key Solution',
                'icon'     => 'tabler-building-arch',
                'excerpt'  => 'Anda cukup menerima kuncinya. Seluruh pekerjaan kami tangani dari awal hingga siap huni.',
                'features' => implode("\n", [
                    'Solusi menyeluruh dari awal hingga serah terima kunci.',
                    'Seluruh keuntungan Paket Gold.',
                    'Pekerjaan sipil dan konstruksi (pembongkaran dinding, plafon, lantai).',
                    'Instalasi kelistrikan, pencahayaan (lighting), dan plumbing.',
                    'Dekorasi akhir (styling accessories & soft furnishing).',
                ]),
                'description' => implode("\n\n", [
                    'Paket Platinum adalah solusi menyeluruh: satu tim, satu tanggung jawab, dari gambar pertama sampai kunci diserahkan.',
                    'Lingkupnya mencakup pekerjaan sipil — pembongkaran dinding, pengerjaan plafon, dan penggantian lantai — beserta seluruh instalasi kelistrikan, tata cahaya, dan plumbing.',
                    'Sentuhan terakhir berupa styling: pemilihan aksesori, tanaman, dan soft furnishing seperti gorden, karpet, serta bantal, agar ruangan langsung terasa hidup saat Anda masuk.',
                ]),
                'sort_order'  => 3,
                'is_featured' => false,
            ],
        ];

        $slugPaket = array_column($paket, 'slug');

        foreach ($paket as $isi) {
            Service::updateOrCreate(
                ['slug' => $isi['slug']],
                $isi + ['is_active' => true],
            );
        }

        // Layanan lama (versi sebelum paket) disembunyikan, bukan dihapus.
        $disembunyikan = Service::whereNotIn('slug', $slugPaket)->update(['is_active' => false]);

        if ($disembunyikan > 0) {
            $this->command?->info("{$disembunyikan} layanan lama disembunyikan (tidak dihapus).");
        }
    }
}
