<?php

namespace Database\Seeders;

use App\Models\Session;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Session::truncate();


            Session::create([
                'type'=> 'rattrapage',
                'session'=> 'rattrapage'
            ]);

            Session::create([
                'type'=> 'session normale',
                'session'=> 'session normale'
            ]);



    }
}
