<?php

namespace Database\Seeders;

use App\Models\Parcour;
use Illuminate\Database\Seeder;


class ParcourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private function parcours(){

        $parcour_list=['MATH','PC','SVT','CHIMIE'];
        $result=[];

        foreach($parcour_list as $key=>$value){

            for ($i=1; $i <= 3 ; $i++) { 

                $result[]=[
                    'parcour'=>'L'.$i.'_'.$value,
                    'grade_id' => $i,
                    'grade'  => 'L',
                    'mention_id' => $key+1
                ];

            }

        }

        return $result;

    }

    public function run()
    {
        Parcour::truncate();

        // foreach($this->parcours() as $key=>$parcour){

        //     Parcour::create([
        //         'parcour'=> $parcour['parcour'],
        //         'mention_id' => $parcour['mention_id'],
        //         'grade_id'   => $parcour['grade_id'],
        //         'abreviation'=> $parcour['parcour'],
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ]);

        // }

    }
}
