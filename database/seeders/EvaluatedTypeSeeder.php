<?php

namespace Database\Seeders;

use App\Models\EvaluatedType;
use Illuminate\Database\Seeder;

class EvaluatedTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EvaluatedType::create(['libelle' => 'devoir de classe']);
        EvaluatedType::create(['libelle' => 'devoir de niveau']);
        EvaluatedType::create(['libelle' => 'interrogation']);
    }
}
