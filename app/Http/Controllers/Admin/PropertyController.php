<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Support\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function __construct(private readonly ImageService $images)
    {
    }

    public function index(Request $request): View
    {
        $properties = Property::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->filled('status'), fn ($q) => $q->where('listing_status', $request->string('status')))
            ->withCount('images')
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('admin.properties.index', [
            'properties' => $properties,
            'types'      => Property::query()->whereNotNull('type')->distinct()->orderBy('type')->pluck('type'),
        ]);
    }

    public function create(): View
    {
        return view('admin.properties.form', ['property' => new Property()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->images->store($request->file('cover_image'), 'properties');
        }

        $property = Property::create($data);
        $this->simpanTerjemahan($property, $request);

        return redirect()->route('admin.properties.edit', $property)
            ->with('success', 'Properti berhasil dibuat. Silakan tambahkan foto galeri.');
    }

    public function edit(Property $property): View
    {
        $property->load('images');

        return view('admin.properties.form', compact('property'));
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $data = $this->validated($request, $property);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->images->store($request->file('cover_image'), 'properties', $property->cover_image);
        }

        $property->update($data);
        $this->simpanTerjemahan($property, $request);

        return redirect()->route('admin.properties.edit', $property)->with('success', 'Properti berhasil diperbarui.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        // Berkas gambar dihapus lebih dulu, sebelum barisnya ikut terhapus cascade.
        $this->images->delete($property->cover_image);
        $property->images->each(fn ($image) => $this->images->delete($image->path));

        $property->delete();

        return redirect()->route('admin.properties.index')->with('success', 'Properti berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?Property $property = null): array
    {
        $request->merge([
            'slug'  => Str::slug($request->input('slug') ?: $request->input('title')),
            // Admin boleh mengetik "1.500.000.000" atau "1500000000" — keduanya diterima.
            'price' => $this->bersihkanHarga($request->input('price')),
        ]);

        $data = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => ['required', 'string', 'max:255', Rule::unique('properties', 'slug')->ignore($property?->id)],
            'type'           => ['nullable', 'string', 'max:100'],
            'listing_status' => ['required', Rule::in(Property::STATUSES)],
            'price'          => ['nullable', 'numeric', 'min:0', 'max:999999999999'],
            'price_note'     => ['nullable', 'string', 'max:60'],
            'location'       => ['nullable', 'string', 'max:150'],
            'address'        => ['nullable', 'string', 'max:300'],
            'land_area'      => ['nullable', 'integer', 'min:0', 'max:16777215'],
            'building_area'  => ['nullable', 'integer', 'min:0', 'max:16777215'],
            'bedrooms'       => ['nullable', 'integer', 'min:0', 'max:255'],
            'bathrooms'      => ['nullable', 'integer', 'min:0', 'max:255'],
            'carports'       => ['nullable', 'integer', 'min:0', 'max:255'],
            'floors'         => ['nullable', 'integer', 'min:0', 'max:255'],
            'certificate'    => ['nullable', 'string', 'max:50'],
            'year_built'     => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 5)],
            'excerpt'        => ['nullable', 'string', 'max:500'],
            'description'    => ['nullable', 'string'],
            'cover_image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
        ], [], [
            'title'          => 'nama properti',
            'listing_status' => 'status',
            'price'          => 'harga',
            'price_note'     => 'keterangan harga',
            'location'       => 'lokasi',
            'address'        => 'alamat',
            'land_area'      => 'luas tanah',
            'building_area'  => 'luas bangunan',
            'bedrooms'       => 'kamar tidur',
            'bathrooms'      => 'kamar mandi',
            'carports'       => 'carport',
            'floors'         => 'jumlah lantai',
            'certificate'    => 'sertifikat',
            'year_built'     => 'tahun dibangun',
            'excerpt'        => 'ringkasan',
            'description'    => 'deskripsi',
            'cover_image'    => 'gambar sampul',
            'sort_order'     => 'urutan',
        ]);

        unset($data['cover_image']);
        $data['sort_order']  = $request->integer('sort_order');
        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }

    /**
     * Ubah harga yang diketik bebas menjadi angka murni.
     * "Rp 1.500.000.000" dan "1500000000" sama-sama menjadi 1500000000.
     */
    private function bersihkanHarga(mixed $nilai): ?string
    {
        $teks = preg_replace('/[^0-9]/', '', (string) $nilai);

        return $teks === '' ? null : $teks;
    }

    /** Simpan versi Inggris dari kolom yang bisa diterjemahkan. */
    private function simpanTerjemahan(\Illuminate\Database\Eloquent\Model $model, Request $request): void
    {
        $model->setTranslation('en', (array) $request->input('en', []));
        $model->save();
    }
}
