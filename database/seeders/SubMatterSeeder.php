<?php

namespace Database\Seeders;

use App\Models\SubMatter;
use Illuminate\Database\Seeder;

class SubMatterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubMatter::create(['libelle' => 'Composition Française', 'symbol' => 'CF', 'matter_id' => 2]); // Expression Ecrit
        SubMatter::create(['libelle' => 'Orthographe-Grammaire', 'symbol' => 'OG', 'matter_id' => 2]);
        SubMatter::create(['libelle' => 'Expression Orale', 'symbol' => 'E0', 'matter_id' => 2]);
    }
}
