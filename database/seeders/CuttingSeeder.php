<?php

namespace Database\Seeders;

use App\Models\Cutting;
use Illuminate\Database\Seeder;

class CuttingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cutting::create(['libelle' => 'trimestre 1', 'value' => '1', 'end' => '0', 'type' => '3']);
        Cutting::create(['libelle' => 'trimestre 2', 'value' => '2', 'end' => '0', 'type' => '3']);
        Cutting::create(['libelle' => 'trimestre 3', 'value' => '2', 'end' => '1', 'type' => '3']);
        Cutting::create(['libelle' => 'semestre 1', 'value' => '1', 'end' => '0', 'type' => '2']);
        Cutting::create(['libelle' => 'semestre 2', 'value' => '2', 'end' => '1', 'type' => '2']);
    }
}
