<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Models\Terrain;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $reviews = Review::with(['terrain', 'user'])
            ->latest()
            ->paginate(10);

        return response()->json(['reviews' => $reviews]);
    }

    public function create()
    {
        return response()->json(['message' => 'Create review form']);
    }

    public function store(StoreReviewRequest $request)
    {
        $data = $request->validated();
        $terrain = Terrain::findOrFail($data['terrain_id']);
        
        // Check if user owns the terrain
        if ($terrain->owner_id === auth()->id()) {
            return response()->json(['message' => 'Cannot review your own terrain'], 403);
        }
        
        // Check if user has a completed booking for this terrain
        $hasCompletedBooking = auth()->user()->bookings()
            ->where('terrain_id', $terrain->id)
            ->where('status', 'completed')
            ->exists();
            
        if (!$hasCompletedBooking) {
            return response()->json(['message' => 'You must have a completed booking to review this terrain'], 403);
        }
        
        // Check if user already reviewed this terrain
        $hasExistingReview = auth()->user()->reviews()
            ->where('terrain_id', $terrain->id)
            ->exists();
            
        if ($hasExistingReview) {
            return response()->json(['message' => 'You have already reviewed this terrain'], 403);
        }
        
        $data['user_id'] = auth()->id();
        $review = Review::create($data);

        return response()->json(['review' => $review], 201);
    }

    public function show(Review $review)
    {
        $review->load(['terrain', 'user']);
        return response()->json(['review' => $review]);
    }

    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        return response()->json(['review' => $review, 'message' => 'Edit form']);
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);
        $review->update($request->validated());
        return response()->json(['review' => $review]);
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }
}
