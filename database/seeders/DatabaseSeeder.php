<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedSettings();
        $this->seedSliders();
        $this->seedServices();
        $this->seedProjects();
    }

    private function seedAdmin(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@dekorasi.me'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('dekorasi2026'),
            ],
        );
    }

    private function seedSettings(): void
    {
        $settings = [
            'site' => [
                'name'        => 'Dekorasi.me',
                'tagline'     => 'Desain Interior Premium',
                'description' => 'Studio desain interior yang mengubah ruang menjadi pengalaman — dari konsep, visualisasi 3D, hingga eksekusi di lapangan.',
            ],
            'contact' => [
                'phone'    => '021-1234567',
                'whatsapp' => '081234567890',
                'email'    => 'halo@dekorasi.me',
                'address'  => 'Jl. Contoh Raya No. 12, Jakarta Selatan 12345',
                'hours'    => 'Senin – Sabtu, 09.00 – 17.00 WIB',
            ],
            'social' => [
                'instagram' => 'https://instagram.com/dekorasi.me',
            ],
            'seo' => [
                'title'    => 'Jasa Desain Interior Premium — Dekorasi.me',
                'keywords' => 'desain interior, jasa interior, interior rumah, interior kantor, furnitur custom',
            ],
            'about' => [
                'heading'  => 'Detail Kecil, Dampak Besar',
                'subtitle' => 'Kami percaya interior yang baik lahir dari mendengarkan — memahami cara Anda hidup, bekerja, dan menerima tamu, lalu menerjemahkannya menjadi ruang.',
                'body'     => implode("\n\n", [
                    'Dekorasi.me adalah studio desain interior yang berfokus pada ruang hunian dan komersial. Kami memulai setiap proyek dengan percakapan, bukan katalog — karena ruang yang tepat selalu berangkat dari kebiasaan penghuninya.',
                    'Tim kami terdiri dari desainer, drafter, dan pengawas lapangan yang bekerja dalam satu alur. Artinya, apa yang Anda lihat di visualisasi 3D adalah apa yang akan berdiri di ruangan Anda — tanpa kejutan di tengah jalan.',
                    'Kami memilih material dengan pertimbangan iklim tropis, perawatan jangka panjang, dan anggaran yang realistis. Estetika penting, tapi ruang yang mudah dirawat jauh lebih berharga.',
                ]),
                'vision'  => 'Menjadi studio desain interior yang dipercaya karena hasilnya konsisten, bukan karena janjinya.',
                'mission' => implode("\n", [
                    'Merancang ruang yang sesuai kebiasaan dan anggaran klien.',
                    'Menjaga transparansi biaya dan jadwal dari awal hingga serah terima.',
                    'Memilih material yang tahan lama dan mudah dirawat.',
                    'Mengawal pengerjaan di lapangan secara langsung.',
                ]),
                'stat1_value' => '120+', 'stat1_label' => 'Proyek Selesai',
                'stat2_value' => '8',    'stat2_label' => 'Tahun Pengalaman',
                'stat3_value' => '95%',  'stat3_label' => 'Klien Merekomendasikan',
                'stat4_value' => '15',   'stat4_label' => 'Tim Profesional',
            ],
        ];

        foreach ($settings as $group => $items) {
            foreach ($items as $key => $value) {
                // firstOrCreate: nilai yang sudah diubah admin tidak tertimpa saat seeder diulang.
                Setting::firstOrCreate(
                    ['key' => "{$group}.{$key}"],
                    ['value' => $value, 'group' => $group],
                );
            }
        }
    }

    private function seedSliders(): void
    {
        if (Slider::exists()) {
            return;
        }

        $slides = [
            [
                'title'       => 'Ruang yang Bercerita Tentang Anda',
                'subtitle'    => 'Desain Interior Premium',
                'description' => 'Kami merancang interior yang bukan sekadar indah dipandang, tapi nyaman dihuni setiap hari.',
                'cta_label'   => 'Lihat Portofolio',
                'cta_url'     => '/proyek',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Dari Konsep Hingga Serah Terima',
                'subtitle'    => 'Satu Tim, Satu Tanggung Jawab',
                'description' => 'Desainer, drafter, dan pengawas lapangan kami bekerja dalam satu alur agar hasilnya sesuai rencana.',
                'cta_label'   => 'Layanan Kami',
                'cta_url'     => '/layanan',
                'sort_order'  => 2,
            ],
        ];

        foreach ($slides as $slide) {
            Slider::create($slide);
        }
    }

    private function seedServices(): void
    {
        if (Service::exists()) {
            return;
        }

        $services = [
            [
                'title'   => 'Desain Interior Residensial',
                'icon'    => 'tabler-home',
                'excerpt' => 'Rumah, apartemen, dan hunian pribadi yang dirancang mengikuti kebiasaan penghuninya.',
                'description' => "Kami merancang interior hunian mulai dari tata ruang, pemilihan material, pencahayaan, hingga detail furnitur.\n\nProses dimulai dengan survei lokasi dan wawancara kebutuhan: berapa penghuni, bagaimana rutinitas harian, dan gaya seperti apa yang Anda sukai. Hasilnya berupa denah tata ruang, moodboard material, serta visualisasi 3D sebelum satu paku pun dipasang.",
            ],
            [
                'title'   => 'Desain Interior Komersial',
                'icon'    => 'tabler-building-store',
                'excerpt' => 'Kantor, kafe, retail, dan ruang usaha yang mendukung alur kerja sekaligus citra merek.',
                'description' => "Ruang komersial punya tuntutan berbeda: sirkulasi pengunjung, daya tahan material, dan identitas merek harus berjalan bersamaan.\n\nKami merancang layout yang efisien untuk operasional harian, memilih material yang tahan pemakaian intensif, dan memastikan setiap sudut memperkuat citra usaha Anda.",
            ],
            [
                'title'   => 'Visualisasi 3D & Rendering',
                'icon'    => 'tabler-cube-3d-sphere',
                'excerpt' => 'Lihat hasil akhir ruangan Anda secara realistis sebelum pengerjaan dimulai.',
                'description' => "Visualisasi 3D menghilangkan tebak-tebakan. Anda bisa menilai proporsi, warna, dan pencahayaan ruangan sebelum mengeluarkan biaya pengerjaan.\n\nKami menyediakan render foto-realistis dari beberapa sudut pandang, lengkap dengan simulasi pencahayaan siang dan malam.",
            ],
            [
                'title'   => 'Furnitur Custom',
                'icon'    => 'tabler-armchair',
                'excerpt' => 'Perabot dibuat sesuai ukuran ruang — memaksimalkan setiap sentimeter yang tersedia.',
                'description' => "Furnitur pabrikan jarang pas untuk ruang dengan bentuk tidak biasa. Kami merancang dan memproduksi perabot custom: kitchen set, wardrobe, meja kerja, hingga partisi.\n\nSetiap unit dibuat berdasarkan pengukuran lapangan, dengan pilihan material dan finishing yang bisa Anda sesuaikan.",
            ],
            [
                'title'   => 'Konsultasi & Perencanaan',
                'icon'    => 'tabler-messages',
                'excerpt' => 'Butuh arahan sebelum memutuskan? Mulai dari sesi konsultasi tanpa komitmen.',
                'description' => "Jika Anda masih menimbang konsep, anggaran, atau tahapan pengerjaan, sesi konsultasi membantu memetakan prioritas.\n\nKami bantu menghitung estimasi biaya, menyusun tahapan pengerjaan, dan memberi rekomendasi material sesuai anggaran.",
            ],
            [
                'title'   => 'Pengawasan Pengerjaan',
                'icon'    => 'tabler-tools',
                'excerpt' => 'Kami mengawal pelaksanaan di lapangan agar hasilnya sesuai gambar kerja.',
                'description' => "Desain yang bagus bisa gagal di tahap pelaksanaan. Karena itu kami menyediakan layanan pengawasan berkala.\n\nAnda menerima laporan progres rutin, dokumentasi foto, dan catatan bila ada penyesuaian di lapangan.",
            ],
        ];

        foreach ($services as $index => $service) {
            Service::create($service + [
                'slug'       => str($service['title'])->slug()->value(),
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function seedProjects(): void
    {
        if (Project::exists()) {
            return;
        }

        $projects = [
            [
                'title'    => 'Rumah Minimalis Modern Bintaro',
                'category' => 'Residensial',
                'client'   => 'Keluarga Wijaya',
                'location' => 'Bintaro, Tangerang Selatan',
                'area'     => '180 m²',
                'year'     => 2025,
                'excerpt'  => 'Hunian dua lantai dengan palet netral hangat dan bukaan lebar untuk memaksimalkan cahaya alami.',
                'description' => "Klien menginginkan rumah yang terasa lapang meski luas tanahnya terbatas. Kami membuka sekat antara ruang tamu, ruang keluarga, dan dapur menjadi satu area menerus.\n\nPalet warna dijaga netral — putih hangat, kayu oak, dan aksen hitam matte — supaya ruangan terasa lebih luas. Bukaan jendela diperbesar di sisi selatan untuk cahaya alami sepanjang hari tanpa panas berlebih.\n\nKitchen set dan wardrobe dibuat custom mengikuti bentuk ruang yang tidak simetris, sehingga tidak ada sudut yang terbuang.",
                'is_featured' => true,
            ],
            [
                'title'    => 'Kantor Kreatif Kemang',
                'category' => 'Kantor',
                'client'   => 'PT Kreasi Digital',
                'location' => 'Kemang, Jakarta Selatan',
                'area'     => '320 m²',
                'year'     => 2025,
                'excerpt'  => 'Ruang kerja kolaboratif dengan zona tenang terpisah dan material akustik pada area rapat.',
                'description' => "Tantangan utama proyek ini adalah menyatukan tim yang butuh kolaborasi dengan individu yang butuh konsentrasi.\n\nKami membagi lantai menjadi tiga zona: area terbuka untuk kerja tim, bilik tenang untuk pekerjaan fokus, dan ruang rapat dengan panel akustik. Sirkulasi dirancang agar lalu-lalang tidak memotong area konsentrasi.\n\nMaterial dipilih yang tahan pemakaian intensif: vinyl lantai komersial, meja HPL, dan kursi ergonomis.",
                'is_featured' => true,
            ],
            [
                'title'    => 'Apartemen Studio SCBD',
                'category' => 'Apartemen',
                'client'   => 'Bpk. Andi',
                'location' => 'SCBD, Jakarta Selatan',
                'area'     => '42 m²',
                'year'     => 2024,
                'excerpt'  => 'Unit studio kecil yang terasa dua kali lebih lapang lewat furnitur multifungsi dan cermin strategis.',
                'description' => "Luas 42 m² menuntut setiap perabot bekerja ganda. Tempat tidur dilengkapi laci penyimpanan, meja kerja dapat dilipat, dan partisi berfungsi sekaligus sebagai rak.\n\nCermin besar dipasang di sisi berlawanan jendela untuk melipatgandakan kesan ruang dan memantulkan cahaya. Palet warna terang dengan aksen kayu menjaga ruangan tetap hangat.",
                'is_featured' => true,
            ],
            [
                'title'    => 'Kafe Industrial Senopati',
                'category' => 'Kafe & Restoran',
                'client'   => 'Kopi Ruang',
                'location' => 'Senopati, Jakarta Selatan',
                'area'     => '150 m²',
                'year'     => 2024,
                'excerpt'  => 'Bata ekspos, besi hitam, dan pencahayaan hangat membentuk suasana yang mengundang untuk berlama-lama.',
                'description' => "Konsep industrial dipilih agar biaya finishing efisien tanpa mengurangi karakter. Bata ekspos dan pipa terbuka justru menjadi elemen dekoratif.\n\nTata letak memisahkan area bar, meja komunal, dan sudut tenang untuk pengunjung yang bekerja. Pencahayaan hangat 2700K dipakai untuk mendorong pengunjung berlama-lama.",
                'is_featured' => true,
            ],
            [
                'title'    => 'Rumah Tropis Kontemporer BSD',
                'category' => 'Residensial',
                'client'   => 'Keluarga Santoso',
                'location' => 'BSD City, Tangerang',
                'area'     => '240 m²',
                'year'     => 2023,
                'excerpt'  => 'Ventilasi silang, void tengah, dan material lokal untuk hunian yang sejuk tanpa bergantung pendingin.',
                'description' => "Klien menginginkan rumah yang sejuk secara alami. Kami merancang void di tengah rumah sebagai cerobong udara, dipadu bukaan silang di setiap lantai.\n\nMaterial lokal seperti batu andesit dan kayu bengkirai dipakai untuk mengurangi jejak pengiriman sekaligus memperkuat karakter tropis.",
                'is_featured' => true,
            ],
            [
                'title'    => 'Butik Retail Pacific Place',
                'category' => 'Retail',
                'client'   => 'Aurum Label',
                'location' => 'Jakarta Pusat',
                'area'     => '85 m²',
                'year'     => 2023,
                'excerpt'  => 'Pencahayaan sorot terarah dan display modular yang mudah diubah mengikuti koleksi musiman.',
                'description' => "Toko retail perlu tampil berbeda setiap musim tanpa renovasi ulang. Kami merancang sistem display modular yang bisa dipindah dan disusun ulang oleh staf toko.\n\nPencahayaan memakai track light yang arahnya bisa disesuaikan, sehingga produk unggulan selalu mendapat sorotan utama.",
                'is_featured' => true,
            ],
        ];

        foreach ($projects as $index => $project) {
            Project::create($project + [
                'slug'       => str($project['title'])->slug()->value(),
                'sort_order' => $index + 1,
            ]);
        }
    }
}
