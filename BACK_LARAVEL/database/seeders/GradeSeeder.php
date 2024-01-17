<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;


class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        Grade::truncate();

        Grade::create([
            'grade'=> 'Licence',
            'abreviation' => 'L',
            'niveau'     => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Grade::create([
            'grade'=> 'Licence',
            'abreviation' => 'L',
            'niveau'     => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);


        Grade::create([
            'grade'=> 'Licence',
            'abreviation' => 'L',
            'niveau'     => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Grade::create([
            'grade'=> 'Master',
            'abreviation' => 'M',
            'niveau'     => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);


        Grade::create([
            'grade'=> 'Master',
            'abreviation' => 'M',
            'niveau'     => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
    }
}
