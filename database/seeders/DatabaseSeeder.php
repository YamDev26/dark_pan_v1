<?php

namespace Database\Seeders;

use App\Models\User;
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
            RoleSeeder::class,
            DrenSeeder::class,
            CuttingSeeder::class,
            NationalitySeeder::class,
            BilanMatterSeeder::class,
            MatterSeeder::class,
            SubMatterSeeder::class,
            LevelSeeder::class,
            SerieSeeder::class,
        ]);

        // User::factory(1)->create();
        User::factory()->create();
    }
}
