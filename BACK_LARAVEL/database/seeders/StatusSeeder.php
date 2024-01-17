<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::truncate();

        Status::create([
            'valeur'=>'passant',
            'abreviation'=> 'P',
        ]);

        Status::create([
            'valeur'=>'redoublant',
            'abreviation'=> 'R',
        ]);
    }
}
