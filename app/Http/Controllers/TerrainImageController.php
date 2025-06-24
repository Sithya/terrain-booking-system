<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTerrainImageRequest;
use App\Http\Requests\UpdateTerrainImageRequest;
use App\Models\Terrain;
use App\Models\TerrainImage;
use Illuminate\Support\Facades\Storage;

class TerrainImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(TerrainImage::class, 'terrain_image');
    }

    public function index(Terrain $terrain)
    {
        $this->authorize('view', $terrain);
        $images = $terrain->images()->latest('uploaded_at')->get();

        return view('terrain-images.index', compact('terrain', 'images'));
    }

    public function create(Terrain $terrain)
    {
        $this->authorize('update', $terrain);
        return view('terrain-images.create', compact('terrain'));
    }

    public function store(StoreTerrainImageRequest $request, Terrain $terrain)
    {
        $imagePath = $request->file('image')->store('terrain-images', 'public');

        $terrain->images()->create([
            'image_path' => $imagePath,
            'uploaded_at' => now(),
        ]);

        return redirect()->route('terrain-images.index', $terrain)
            ->with('success', 'Image uploaded successfully.');
    }

    public function show(TerrainImage $terrainImage)
    {
        return view('terrain-images.show', compact('terrainImage'));
    }

    public function edit(TerrainImage $terrainImage)
    {
        return view('terrain-images.edit', compact('terrainImage'));
    }

    public function update(UpdateTerrainImageRequest $request, TerrainImage $terrainImage)
    {
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($terrainImage->image_path);
            $imagePath = $request->file('image')->store('terrain-images', 'public');

            $terrainImage->update([
                'image_path' => $imagePath,
                'uploaded_at' => now(),
            ]);
        }

        return redirect()->route('terrain-images.index', $terrainImage->terrain)
            ->with('success', 'Image updated successfully.');
    }

    public function destroy(TerrainImage $terrainImage)
    {
        $terrain = $terrainImage->terrain;
        Storage::disk('public')->delete($terrainImage->image_path);
        $terrainImage->delete();

        return redirect()->route('terrain-images.index', $terrain)
            ->with('success', 'Image deleted successfully.');
    }
}
