<?php

namespace Database\Seeders;

use App\Models\Semestre;
use Illuminate\Database\Seeder;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Semestre::truncate();
        Semestre::insert([
            ['semestre'=>'Pair'],
            ['semestre'=>'Impair'],
        ]);
    }
}
