<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Database\Seeder;

/**
 * Mengisi versi bahasa Inggris untuk konten bawaan.
 *
 *   php artisan db:seed --class=TerjemahanInggrisSeeder --force
 *
 * Aman dijalankan ulang: hanya menyentuh baris yang cocok berdasarkan slug,
 * dan tidak menimpa terjemahan yang sudah Anda isi sendiri lewat dashboard.
 */
class TerjemahanInggrisSeeder extends Seeder
{
    public function run(): void
    {
        $this->sliders();
        $this->paket();
        $this->proyek();
        $this->pengaturan();

        $this->command?->info('Terjemahan bahasa Inggris selesai diisi.');
    }

    private function sliders(): void
    {
        $data = [
            'Ruang yang Bercerita Tentang Anda' => [
                'title'       => 'Spaces That Tell Your Story',
                'subtitle'    => 'Premium Interior Design',
                'description' => 'We design interiors that are not merely beautiful to look at, but comfortable to live in every day.',
                'cta_label'   => 'View Portfolio',
            ],
            'Dari Konsep Hingga Serah Terima' => [
                'title'       => 'From Concept to Handover',
                'subtitle'    => 'One Team, One Responsibility',
                'description' => 'Our designers, drafters, and site supervisors work as one so the result matches the plan.',
                'cta_label'   => 'Our Packages',
            ],
        ];

        foreach ($data as $judul => $terjemahan) {
            $this->terapkan(Slider::where('title', $judul)->first(), $terjemahan);
        }
    }

    private function paket(): void
    {
        $data = [
            'paket-silver' => [
                'title'    => 'Silver Package',
                'subtitle' => 'Design Only',
                'excerpt'  => 'Concept, layout, 3D visuals, and technical drawings — ready to hand to any contractor.',
                'description' => "The Silver Package focuses on design. You receive a complete set of documents that any contractor can build from.\n\nIt suits owners who already have a trusted builder, or who want to phase the work while keeping the design direction consistent.",
                'features' => "Initial concept consultation.\nRoom layout (2D Layout Plan).\nDesign visualisation (3D rendering, up to 3 views).\nTechnical production drawings.",
            ],
            'paket-gold' => [
                'title'    => 'Gold Package',
                'subtitle' => 'Design + Custom Furniture',
                'excerpt'  => 'Everything in Silver, plus manufacturing and installation of custom furniture.',
                'description' => "Beyond the design, we manufacture and install the custom furniture ourselves — so the sizes match the room exactly and the finish matches the concept.\n\nThis is the most popular option for homes and apartments whose structure is already sound and only need the interior fitted out.",
                'features' => "Everything in the Silver Package.\nManufacture and installation of custom furniture (cabinets, beds, wardrobes, and more).\nFinishing material selection (HPL, Duco, Veneer).\nProduct installation supervision.",
            ],
            'paket-platinum' => [
                'title'    => 'Platinum Package',
                'subtitle' => 'Full Turn-Key Solution',
                'excerpt'  => 'A complete solution from start to handover — you receive the keys ready to use.',
                'description' => "You hand over the space, we return it ready to use. Every stage — demolition, ceilings, flooring, electrical, lighting, plumbing, furniture, right down to the final styling — is handled by one team.\n\nOne point of contact, one schedule, one accountability. Ideal for full renovations and new commercial fit-outs.",
                'features' => "A complete solution from start to key handover.\nEverything in the Gold Package.\nCivil and construction work (wall demolition, ceilings, flooring).\nElectrical, lighting, and plumbing installation.\nFinal decoration (styling accessories & soft furnishing).",
            ],
        ];

        foreach ($data as $slug => $terjemahan) {
            $this->terapkan(Service::where('slug', $slug)->first(), $terjemahan);
        }
    }

    private function proyek(): void
    {
        $data = [
            'rumah-minimalis-modern-bintaro' => [
                'description' => "The client wanted a home that felt open despite a limited plot. We removed the partitions between the living room, family room, and kitchen to form one continuous space.

The colour palette was kept neutral — warm white, oak, and matte black accents — to make the rooms feel larger. Windows on the southern side were enlarged for daylight throughout the day without excess heat.

The kitchen set and wardrobes were custom made to follow the room's irregular shape, so no corner goes to waste.",
                'title'    => 'Modern Minimalist House, Bintaro',
                'category' => 'Residential',
                'area'     => '180 sqm',
                'excerpt'  => 'A two-storey home with a warm neutral palette and wide openings that maximise natural light.',
            ],
            'kantor-kreatif-kemang' => [
                'description' => "The main challenge was uniting a team that needs collaboration with individuals who need concentration.

We divided the floor into three zones: an open area for teamwork, quiet booths for focused work, and meeting rooms with acoustic panels. Circulation was planned so foot traffic never cuts through the focus area.

Materials were chosen for heavy daily use: commercial vinyl flooring, HPL desks, and ergonomic chairs.",
                'title'    => 'Creative Office, Kemang',
                'category' => 'Office',
                'area'     => '320 sqm',
                'excerpt'  => 'A collaborative workspace with a separate quiet zone and acoustic treatment in the meeting rooms.',
            ],
            'apartemen-studio-scbd' => [
                'description' => "At 42 sqm, every piece of furniture has to work twice. The bed includes storage drawers, the desk folds away, and the partition doubles as shelving.

A large mirror faces the window to multiply the sense of space and bounce daylight deeper into the room. A light palette with wood accents keeps the space warm.",
                'title'    => 'Studio Apartment, SCBD',
                'category' => 'Apartment',
                'area'     => '42 sqm',
                'excerpt'  => 'A compact studio that feels twice as spacious through multifunctional furniture and well-placed mirrors.',
            ],
            'kafe-industrial-senopati' => [
                'description' => "The industrial concept was chosen to keep finishing costs efficient without losing character. Exposed brick and open pipework became decorative elements in their own right.

The layout separates the bar, communal tables, and a quiet corner for guests who work. Warm 2700K lighting encourages visitors to linger.",
                'title'    => 'Industrial Cafe, Senopati',
                'category' => 'Cafe & Restaurant',
                'area'     => '150 sqm',
                'excerpt'  => 'Exposed brick, black steel, and warm lighting create an atmosphere that invites guests to linger.',
            ],
            'rumah-tropis-kontemporer-bsd' => [
                'description' => "The client wanted a house that stays cool naturally. We designed a central void to act as a chimney for air, paired with cross ventilation on every floor.

Local materials such as andesite stone and bengkirai wood reduce the shipping footprint while reinforcing the tropical character.",
                'title'    => 'Contemporary Tropical House, BSD',
                'category' => 'Residential',
                'area'     => '240 sqm',
                'excerpt'  => 'Cross ventilation, a central void, and local materials keep the home cool without relying on air conditioning.',
            ],
            'butik-retail-pacific-place' => [
                'description' => "Retail stores need to look different each season without renovating. We designed a modular display system that store staff can move and rearrange themselves.

Lighting uses track lights with adjustable direction, so featured products always get the spotlight.",
                'title'    => 'Retail Boutique, Pacific Place',
                'category' => 'Retail',
                'area'     => '85 sqm',
                'excerpt'  => 'Directional spotlighting and modular displays that staff can rearrange for each seasonal collection.',
            ],
        ];

        foreach ($data as $slug => $terjemahan) {
            $this->terapkan(Project::where('slug', $slug)->first(), $terjemahan);
        }
    }

    private function pengaturan(): void
    {
        $data = [
            'site.tagline_en'     => 'Premium Interior Design',
            'site.description_en' => 'An interior design studio that turns spaces into experiences — from concept and 3D visualisation through to execution on site.',
            'seo.title_en'        => 'Premium Interior Design Service — Dekorasi.me',

            'about.stat1_label_en' => 'Projects Completed',
            'about.stat2_label_en' => 'Years of Experience',
            'about.stat3_label_en' => 'Clients Recommend Us',
            'about.stat4_label_en' => 'Professional Team',

            'about.heading_en'  => 'Small Details, Great Impact',
            'about.subtitle_en' => 'We believe good interiors begin with listening — understanding how you live, work, and welcome guests, then translating that into space.',
            'about.body_en'     => implode("\n\n", [
                'Dekorasi.me is an interior design studio focused on residential and commercial spaces. Every project begins with a conversation rather than a catalogue — because the right space always starts from the habits of the people who live in it.',
                'Our team brings designers, drafters, and site supervisors into a single workflow. That means what you see in the 3D visualisation is what will stand in your room — with no surprises along the way.',
                'We select materials with the tropical climate, long-term maintenance, and a realistic budget in mind. Aesthetics matter, but a space that is easy to care for is worth far more.',
            ]),
            'about.vision_en'  => 'To become a trusted provider of integrated interior and furniture solutions in Indonesia, creating spaces that are innovative, functional, and of high aesthetic value for every generation.',
            'about.mission_en' => implode("\n", [
                'Prime Quality : Delivering furniture and interior renovation work built to the best material standards.',
                'Design Innovation : Continually adapting to global design trends to offer fresh, creative concepts.',
                'Client Satisfaction : Prioritising transparent communication and personalised design to meet client expectations.',
                'Punctuality : Maintaining professional efficiency so every project finishes on the agreed schedule.',
            ]),
        ];

        foreach ($data as $key => $nilai) {
            // firstOrCreate: terjemahan yang sudah Anda ubah sendiri tidak tertimpa.
            Setting::firstOrCreate(['key' => $key], ['value' => $nilai, 'group' => explode('.', $key)[0]]);
        }
    }

    /** @param array<string, string> $terjemahan */
    private function terapkan(mixed $model, array $terjemahan): void
    {
        if (! $model || $model->hasTranslation('en')) {
            return;
        }

        $model->setTranslation('en', $terjemahan);
        $model->save();
    }
}
