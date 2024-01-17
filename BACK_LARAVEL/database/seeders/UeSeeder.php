<?php

namespace Database\Seeders;

use App\Models\Ue;
use Illuminate\Database\Seeder;

class UeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ue::truncate();

        Ue::create([
            'ue'        => 'mathématique'
        ]);

        Ue::create([
            'ue'        => 'physique'
        ]);

        Ue::create([
            'ue'        => 'chimie'
        ]);

        Ue::create([
            'ue'        => 'communication'
        ]);
    }
}
