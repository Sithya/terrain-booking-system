<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavoriteRequest;
use App\Models\Favorite;
use App\Models\Terrain;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $favorites = auth()->user()->favorites()
            ->with(['terrain.owner', 'terrain.images'])
            ->latest()
            ->paginate(12);

        return response()->json(['favorites' => $favorites]);
    }

    public function create()
    {
        return response()->json(['message' => 'Create favorite form']);
    }

    public function store(StoreFavoriteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        
        // Check if favorite already exists
        $existingFavorite = Favorite::where('user_id', auth()->id())
            ->where('terrain_id', $data['terrain_id'])
            ->first();
            
        if ($existingFavorite) {
            return response()->json(['message' => 'Terrain already in favorites'], 422);
        }
        
        $favorite = Favorite::create($data);

        return response()->json(['favorite' => $favorite], 201);
    }

    public function show(Favorite $favorite)
    {
        $this->authorize('view', $favorite);
        $favorite->load(['terrain', 'user']);
        return response()->json(['favorite' => $favorite]);
    }

    public function destroy(Favorite $favorite)
    {
        $this->authorize('delete', $favorite);
        $favorite->delete();
        return response()->json(['message' => 'Removed from favorites']);
    }
}
