<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Terrain;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Booking::class, 'booking');
    }

    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with(['terrain', 'payments'])
            ->latest()
            ->paginate(10);

        return response()->json(['bookings' => $bookings]);
    }

    public function create()
    {
        return response()->json(['message' => 'Create booking form']);
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();
        
        $terrain = Terrain::findOrFail($data['terrain_id']);
        
        // Check if user is trying to book their own terrain
        if ($terrain->owner_id === auth()->id()) {
            return response()->json(['message' => 'Cannot book your own terrain'], 403);
        }
        
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $duration = $startDate->diffInDays($endDate) + 1;
        
        $data['total_price'] = $terrain->price_per_day * $duration;
        $data['renter_id'] = auth()->id();

        $booking = Booking::create($data);

        return response()->json(['booking' => $booking], 201);
    }

    public function show(Booking $booking)
    {
        $booking->load(['terrain', 'renter', 'payments']);
        return response()->json(['booking' => $booking]);
    }

    public function edit(Booking $booking)
    {
        return response()->json(['booking' => $booking, 'message' => 'Edit form']);
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $data = $request->validated();

        if (isset($data['start_date']) && isset($data['end_date'])) {
            $startDate = Carbon::parse($data['start_date']);
            $endDate = Carbon::parse($data['end_date']);
            $duration = $startDate->diffInDays($endDate) + 1;
            $data['total_price'] = $booking->terrain->price_per_day * $duration;
        }

        $booking->update($data);
        return response()->json(['booking' => $booking]);
    }

    public function destroy(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Booking cancelled successfully']);
    }
}
