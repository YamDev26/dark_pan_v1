<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::create(['libelle' => 'sixième', 'symbol' => '6eme', 'cycle1' => '1']);
        Level::create(['libelle' => 'cinquième', 'symbol' => '5eme', 'cycle1' => '1']);
        Level::create(['libelle' => 'quatrième', 'symbol' => '4eme', 'cycle1' => '1']);
        Level::create(['libelle' => 'troisième', 'symbol' => '3eme', 'cycle1' => '1']);
        Level::create(['libelle' => 'séconde', 'symbol' => '2nde', 'cycle2' => '1']);
        Level::create(['libelle' => 'première', 'symbol' => '1ere', 'cycle2' => '1']);
        Level::create(['libelle' => 'terminale', 'symbol' => 'Tle', 'cycle2' => '1']);
    }
}
