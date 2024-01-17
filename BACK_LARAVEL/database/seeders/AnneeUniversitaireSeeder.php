<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnneeUniversitaire;


class AnneeUniversitaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AnneeUniversitaire::truncate();

        AnneeUniversitaire::create([
            'valeur'=> '2020-2021',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        AnneeUniversitaire::create([
            'valeur'=> '2021-2022',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
