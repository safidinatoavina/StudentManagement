<?php

namespace Database\Seeders;

use App\Models\Mention;
use Illuminate\Database\Seeder;


class MentionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mention::truncate();

        Mention::create([
            'mention'=> 'mathématiques et informatique',
            'abreviation' => 'MI',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Mention::create([
            'mention'=> 'science naturel',
            'abreviation' => 'SN',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Mention::create([
            'mention'=> 'physique',
            'abreviation' => 'SN',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Mention::create([
            'mention'=> 'chimie',
            'abreviation' => 'SN',
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
