<?php

namespace Database\Seeders;

use App\Models\DaysWeek;
use Illuminate\Database\Seeder;

class DaysWeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DaysWeek::create(['libelle' => 'lundi', 'order' => 1]);
        DaysWeek::create(['libelle' => 'mardi', 'order' => 2]);
        DaysWeek::create(['libelle' => 'mercredi', 'order' => 3]);
        DaysWeek::create(['libelle' => 'jeudi', 'order' => 4]);
        DaysWeek::create(['libelle' => 'vendredi', 'order' => 5]);
    }
}
