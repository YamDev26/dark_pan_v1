<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['libelle' => 'SuperAdmin', 'status' => '0']);
        Role::create(['libelle' => 'admin']);
        Role::create(['libelle' => 'fondateur']);
        Role::create(['libelle' => 'directeur']);
        Role::create(['libelle' => 'educateur']);
        Role::create(['libelle' => 'comptable']);
        Role::create(['libelle' => 'secretaire']);
        Role::create(['libelle' => 'enseignant']); // id = 8
    }
}
