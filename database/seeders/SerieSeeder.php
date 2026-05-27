<?php

namespace Database\Seeders;

use App\Models\Serie;
use Illuminate\Database\Seeder;

class SerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Serie::create(['libelle' => 'A', '2nde' => '1']);
        Serie::create(['libelle' => 'A1', '1ere' => '1', 'tle' => '1']);
        Serie::create(['libelle' => 'A2', '1ere' => '1', 'tle' => '1']);
        Serie::create(['libelle' => 'C', '2nde' => '1', '1ere' => '1', 'tle' => '1']);
        Serie::create(['libelle' => 'D', '1ere' => '1', 'tle' => '1']);
    }
}
