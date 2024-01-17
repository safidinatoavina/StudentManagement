<?php

namespace App\Console\Commands\simulation;

use Excel;
use App\Models\Parcour;
use Illuminate\Console\Command;
use App\Models\AnneeUniversitaire;
use App\Exports\simulationEtudiant;


class etudiantFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulation:export-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'exportation excel ';

    /**
     * Create a new command instance.
     *
     * @return void
     */

     private   $data=[
        ['','','','','','','','','']
     ];


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $faker = \Faker\Factory::create();

        $input=$this->getInput();


        $parcours=Parcour::all()
                    ->map(function ($parcour){
                        return $parcour->abreviation;
                    });

        for ($i=0; $i < $input['nombre']; $i++) { 


            $etudiant=[
                strtoupper($faker->lastName),
                $faker->firstName,
                random_int(1980,2007)."-".random_int(1,12)."-".random_int(1,28),
                $faker->city,
                $faker->address,
                $faker->isbn10,
                $faker->ean13,
                $parcours->random(),
                collect(['P','R'])->random()
            ];

            $this->data[]=$etudiant;
        }


        Excel::store(new simulationEtudiant($this->data), "public/simulation_etudiant.".$input['type']);

        return 0;
    }

    private function getInput(){

        do{
            $type = $this->ask('type de fichier de sortie ? (csv,xlsx)');
        }while(!$type);

        do{
            $nombre = $this->ask('quel est le nombre des etudiants à exporter ?');
        }while(!$nombre);



        return [
            'type'  => $type,
            'nombre'=> $nombre
        ];

    }
}
