<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Support\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PropertyImageController extends Controller
{
    public function __construct(private readonly ImageService $images)
    {
    }

    /** Unggah satu atau banyak foto sekaligus ke galeri properti. */
    public function store(Request $request, Property $property): RedirectResponse
    {
        $request->validate([
            'images'   => ['required', 'array', 'max:20'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
        ], [], ['images' => 'foto', 'images.*' => 'foto']);

        $order = (int) $property->images()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $property->images()->create([
                'path'       => $this->images->store($file, 'properties/'.$property->id),
                'sort_order' => ++$order,
            ]);
        }

        $count = count($request->file('images'));

        return back()->with('success', "{$count} foto berhasil ditambahkan ke galeri.");
    }

    public function destroy(PropertyImage $image): RedirectResponse
    {
        $this->images->delete($image->path);
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    /** Simpan urutan galeri hasil drag & drop. */
    public function reorder(Request $request, Property $property): JsonResponse
    {
        $validated = $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($validated['order'] as $position => $imageId) {
            $property->images()->whereKey($imageId)->update(['sort_order' => $position]);
        }

        return response()->json(['status' => 'ok']);
    }
}
