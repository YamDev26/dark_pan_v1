<?php

namespace Database\Seeders;

use App\Models\DevoirsType;
use Illuminate\Database\Seeder;

class DevoirsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DevoirsType::create(['libelle' => 'devoir de classe']);
        DevoirsType::create(['libelle' => 'devoir de niveau']);
    }
}
