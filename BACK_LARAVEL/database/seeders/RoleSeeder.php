<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();

        Role::create(['type'=>'Admin']);
        Role::create(['type'=>'Jury']);
        Role::create(['type'=>'Professeur']);
        Role::create(['type'=>'Secrétaire']);
        Role::create(['type'=>'Réinscription']);
        Role::create(['type'=>'Responsable parcours']);
        Role::create(['type'=>'Opérateur de saisie']);
    }
}
