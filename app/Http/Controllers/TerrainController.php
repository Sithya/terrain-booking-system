<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTerrainRequest;
use App\Http\Requests\UpdateTerrainRequest;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TerrainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $terrains = Terrain::with(['owner', 'images', 'reviews'])
            ->where('is_available', true)
            ->latest()
            ->paginate(12);

        return response()->json(['terrains' => $terrains]);
    }

    public function create()
    {
        return response()->json(['message' => 'Create terrain form']);
    }

    public function store(StoreTerrainRequest $request)
    {
        $data = $request->validated();
        $data['owner_id'] = auth()->id();

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('terrains', 'public');
        }

        $terrain = Terrain::create($data);
        return response()->json(['terrain' => $terrain], 201);
    }

    public function show(Terrain $terrain)
    {
        $terrain->load(['owner', 'images', 'reviews.user', 'bookings']);
        return response()->json(['terrain' => $terrain]);
    }

    public function edit(Terrain $terrain)
    {
        // Check if user owns this terrain
        if (auth()->id() !== $terrain->owner_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return response()->json(['terrain' => $terrain, 'message' => 'Edit form']);
    }

    public function update(UpdateTerrainRequest $request, Terrain $terrain)
    {
        // Check if user owns this terrain
        if (auth()->id() !== $terrain->owner_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $data = $request->validated();

        if ($request->hasFile('main_image')) {
            if ($terrain->main_image) {
                Storage::disk('public')->delete($terrain->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('terrains', 'public');
        }

        $terrain->update($data);
        return response()->json(['terrain' => $terrain]);
    }

    public function destroy(Terrain $terrain)
    {
        // Check if user owns this terrain
        if (auth()->id() !== $terrain->owner_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        if ($terrain->main_image) {
            Storage::disk('public')->delete($terrain->main_image);
        }

        $terrain->delete();
        return response()->json(['message' => 'Terrain deleted successfully']);
    }
}
