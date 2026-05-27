<?php

namespace Database\Seeders;

use App\Models\Notionality;
use Illuminate\Database\Seeder;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notionality::create(['libelle' => 'ivoirienne']);
    }
}
