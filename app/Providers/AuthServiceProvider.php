<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Favorite;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Terrain;
use App\Models\TerrainImage;
use App\Policies\BookingPolicy;
use App\Policies\FavoritePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\TerrainImagePolicy;
use App\Policies\TerrainPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Terrain::class => TerrainPolicy::class,
        TerrainImage::class => TerrainImagePolicy::class,
        Booking::class => BookingPolicy::class,
        Payment::class => PaymentPolicy::class,
        Review::class => ReviewPolicy::class,
        Favorite::class => FavoritePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
