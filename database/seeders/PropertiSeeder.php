<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Contoh isi awal untuk fitur Penjualan Properti.
 *
 * Dijalankan sekali saja: properti yang slug-nya sudah ada dilewati, sehingga
 * seeder aman diulang tanpa menimpa data yang sudah diubah admin.
 */
class PropertiSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->contoh() as $data) {
            $terjemahan = $data['en'];
            unset($data['en']);

            $data['slug'] = Str::slug($data['title']);

            if (Property::where('slug', $data['slug'])->exists()) {
                continue;
            }

            $properti = Property::create($data);
            $properti->setTranslation('en', $terjemahan);
            $properti->save();
        }
    }

    /** @return list<array<string, mixed>> */
    private function contoh(): array
    {
        return [
            [
                'title'          => 'Rumah 2 Lantai Bintaro Sektor 9',
                'type'           => 'Rumah',
                'listing_status' => 'dijual',
                'price'          => 2_850_000_000,
                'location'       => 'Bintaro, Tangerang Selatan',
                'address'        => 'Jl. Maleo Raya No. 21, Sektor 9, Bintaro Jaya, Tangerang Selatan',
                'land_area'      => 180,
                'building_area'  => 210,
                'bedrooms'       => 4,
                'bathrooms'      => 3,
                'carports'       => 2,
                'floors'         => 2,
                'certificate'    => 'SHM',
                'year_built'     => 2021,
                'excerpt'        => 'Rumah siap huni di kawasan tenang, sudah tertata lengkap dengan furnitur custom.',
                'description'    => "Hunian dua lantai di salah satu sektor paling tenang di Bintaro Jaya. Bangunan menghadap timur sehingga ruang keluarga mendapat cahaya pagi yang cukup sepanjang tahun.\n\nSeluruh interior dikerjakan oleh tim kami: dapur custom dengan meja granit, walk-in closet di kamar utama, serta pencahayaan tersembunyi di ruang tamu dan ruang makan.\n\nAkses lima menit ke pintu tol dan sepuluh menit ke stasiun. Sekolah, rumah sakit, dan pusat belanja berada dalam radius dua kilometer.",
                'is_featured'    => true,
                'sort_order'     => 1,
                'en' => [
                    'title'       => 'Two-Storey House, Bintaro Sector 9',
                    'type'        => 'House',
                    'location'    => 'Bintaro, South Tangerang',
                    'certificate' => 'Freehold (SHM)',
                    'excerpt'     => 'A move-in ready home in a quiet neighbourhood, fully styled with custom furniture.',
                    'description' => "A two-storey home in one of the quietest sectors of Bintaro Jaya. The building faces east, so the family room receives soft morning light throughout the year.\n\nThe entire interior was completed by our own team: a custom kitchen with granite countertops, a walk-in closet in the master bedroom, and concealed lighting across the living and dining areas.\n\nFive minutes to the toll gate and ten minutes to the train station. Schools, hospitals, and shopping centres are all within a two-kilometre radius.",
                ],
            ],
            [
                'title'          => 'Apartemen Studio Sudirman Suites',
                'type'           => 'Apartemen',
                'listing_status' => 'disewakan',
                'price'          => 12_500_000,
                'price_note'     => '/ bulan',
                'location'       => 'Sudirman, Jakarta Selatan',
                'address'        => 'Sudirman Suites Tower B, Lantai 21, Jl. Jenderal Sudirman, Jakarta Selatan',
                'building_area'  => 42,
                'bedrooms'       => 1,
                'bathrooms'      => 1,
                'floors'         => 1,
                'certificate'    => 'SHSRS',
                'year_built'     => 2019,
                'excerpt'        => 'Studio full furnished di jantung kawasan bisnis, tinggal bawa koper.',
                'description'    => "Unit studio berperabot lengkap di lantai 21 dengan pemandangan langsung ke arah kawasan Sudirman. Cocok untuk profesional yang bekerja di sekitar SCBD.\n\nSeluruh perabot dipilih dengan skema warna netral agar unit terasa lebih lapang: tempat tidur queen, meja kerja, lemari built-in, dan dapur kecil yang tertata rapi.\n\nFasilitas gedung mencakup kolam renang, pusat kebugaran, dan akses langsung ke stasiun MRT.",
                'is_featured'    => true,
                'sort_order'     => 2,
                'en' => [
                    'title'       => 'Studio Apartment, Sudirman Suites',
                    'type'        => 'Apartment',
                    'location'    => 'Sudirman, South Jakarta',
                    'certificate' => 'Strata Title',
                    'price_note'  => '/ month',
                    'excerpt'     => 'A fully furnished studio in the heart of the business district — just bring your suitcase.',
                    'description' => "A fully furnished studio on the 21st floor with a direct view over the Sudirman business district. Ideal for professionals working around SCBD.\n\nEvery piece of furniture was chosen in a neutral palette to make the unit feel more spacious: a queen bed, a work desk, built-in wardrobes, and a compact, well-organised kitchen.\n\nBuilding facilities include a swimming pool, a fitness centre, and direct access to the MRT station.",
                ],
            ],
            [
                'title'          => 'Ruko 3 Lantai Alam Sutera',
                'type'           => 'Ruko',
                'listing_status' => 'dijual',
                'price'          => 4_200_000_000,
                'price_note'     => 'Nego',
                'location'       => 'Alam Sutera, Tangerang',
                'address'        => 'Jl. Jalur Sutera Barat Blok C No. 8, Alam Sutera, Tangerang',
                'land_area'      => 96,
                'building_area'  => 240,
                'bathrooms'      => 3,
                'carports'       => 2,
                'floors'         => 3,
                'certificate'    => 'HGB',
                'year_built'     => 2020,
                'excerpt'        => 'Ruko hadap jalan utama, cocok untuk kantor atau kafe dengan lalu lintas ramai.',
                'description'    => "Ruko tiga lantai menghadap langsung ke jalur utama Alam Sutera, dengan lebar muka delapan meter yang memberi keleluasaan untuk papan nama besar.\n\nLantai dasar berupa ruang terbuka tanpa kolom tengah, sehingga bebas ditata sebagai galeri, kafe, atau ruang pamer. Lantai dua dan tiga sudah tersekat menjadi ruang kerja dan pantry.\n\nKawasan ini ramai sepanjang hari karena berdekatan dengan perkantoran, kampus, dan pusat kuliner.",
                'sort_order'     => 3,
                'en' => [
                    'title'       => 'Three-Storey Shophouse, Alam Sutera',
                    'type'        => 'Shophouse',
                    'location'    => 'Alam Sutera, Tangerang',
                    'certificate' => 'Right to Build (HGB)',
                    'price_note'  => 'Negotiable',
                    'excerpt'     => 'A main-road shophouse, well suited to an office or café with steady foot traffic.',
                    'description' => "A three-storey shophouse facing the main Alam Sutera thoroughfare, with an eight-metre frontage that leaves plenty of room for large signage.\n\nThe ground floor is an open span with no central columns, so it can be arranged freely as a gallery, café, or showroom. The second and third floors are already partitioned into work areas and a pantry.\n\nThe area stays busy throughout the day thanks to nearby offices, campuses, and food districts.",
                ],
            ],
        ];
    }
}
