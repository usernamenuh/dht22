<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       // User::factory()->create([
         //   'name' => 'Test User',
           // 'email' => 'test@example.com',
        // ]);

        DB::table('smart_homes')->insert([
            ['name' => 'Bathroom', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Living Room', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kitchen', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bedroom', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Garage', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Garden', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('dht22s')->insert([
            ['temperature' => 0, 'humidity' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('settings')->insert([
            ['threshold_temp' => 30, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
