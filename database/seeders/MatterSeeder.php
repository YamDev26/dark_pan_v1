<?php

namespace Database\Seeders;

use App\Models\Matter;
use Illuminate\Database\Seeder;

class MatterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Matter::create(['libelle' => 'Anglais', 'symbol' => 'Ang', 'bilan_matter_id' => 1, 'position' => 3]);
        Matter::create(['libelle' => 'Français', 'symbol' => 'Fr', 'bilan_matter_id' => 1, 'position' => 2]); // id = 2
        Matter::create(['libelle' => 'Histoire-Géographie', 'symbol' => 'HG', 'bilan_matter_id' => 1, 'position' => 5]);
        Matter::create(['libelle' => 'Mathématique', 'symbol' => 'Math', 'bilan_matter_id' => 2, 'position' => 1]);
        Matter::create(['libelle' => 'Physique-Chimie', 'symbol' => 'PC', 'bilan_matter_id' => 2, 'position' => 2]);
        Matter::create(['libelle' => 'Sciences de la vie et de la terre', 'symbol' => 'SVT', 'bilan_matter_id' => 2, 'position' => 3]);
        Matter::create(['libelle' => 'Education physique et sportive', 'symbol' => 'EPS', 'bilan_matter_id' => 3, 'position' => 1]);
        Matter::create(['libelle' => 'All/Esp', 'symbol' => 'LV2', 'bilan_matter_id' => 1, 'position' => 4]); // id = 8
        Matter::create(['libelle' => 'EDHC', 'symbol' => 'EDHC', 'bilan_matter_id' => 3, 'position' => 2]);
        Matter::create(['libelle' => 'Mus/Arts Pl', 'symbol' => 'Mus/AP', 'bilan_matter_id' => 3, 'position' => 3]); // 10
        Matter::create(['libelle' => 'Philosophie', 'symbol' => 'Philo', 'bilan_matter_id' => 1, 'position' => 1]);
        Matter::create(['libelle' => 'Informatique', 'symbol' => 'Tic', 'bilan_matter_id' => 3, 'position' => 4]); // 12
        Matter::create(['libelle' => 'Conduite', 'symbol' => 'Cdte', 'bilan_matter_id' => 3, 'position' => 5]); // id = 13
    }
}
