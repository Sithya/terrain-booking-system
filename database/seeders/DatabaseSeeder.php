<?php



namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test user only if it doesn't exist
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
            ]
        );

        $this->call([
            TerrainSeeder::class,
            TerrainImageSeeder::class,
            BookingSeeder::class,
            PaymentSeeder::class,
            ReviewSeeder::class,
            FavoriteSeeder::class,
        ]);
    }
}
