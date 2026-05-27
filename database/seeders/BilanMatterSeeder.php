<?php

namespace Database\Seeders;

use App\Models\BilanMatter;
use Illuminate\Database\Seeder;

class BilanMatterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BilanMatter::create(['libelle' => 'bilan lettres']);
        BilanMatter::create(['libelle' => 'bilan sciences']);
        BilanMatter::create(['libelle' => 'bilan autres']);
    }
}
