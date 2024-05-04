<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Position::class,
            JobSeeder::class,
            SchoolSeeder::class,
            UserSeeder::class,
            ClassRoomSeeder::class
        ]);
    }
}
