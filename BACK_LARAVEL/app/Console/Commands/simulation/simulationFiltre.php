<?php

namespace App\Console\Commands\simulation;

use App\Models\Ue;
use Faker\Factory;
use App\Models\Note;
use App\Models\NoteTp;
use App\Models\Matiere;
use App\Models\Parcour;
use App\Models\Session;
use App\Models\Etudiant;
use App\Models\Personne;
use App\Models\Semestre;
use App\Models\MoyenneUe;
use App\Models\Historique;
use App\Models\MoyenneAnnee;
use Illuminate\Http\Request;
use App\Models\MoyenneMatiere;
use App\Models\MoyenneSemestre;
use Illuminate\Console\Command;
use App\Service\Filter\FilterService;
use Illuminate\Support\Facades\Schema;
use App\Service\Moyenne\MoyenneService;


class simulationFiltre extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulation:filtre';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'simulation de donne avec une compte temps';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $file_path;

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
        $this->time_update_note();
        return 0;
    }

    public function time_update_note(){
        $this->file_path = storage_path('app/nom_du_fichier1.txt');
        $this->viderData();
        $numbers= [];
        for ($i=10; $i<=10000 ; $i+=10) {
            $numbers[]=$i;
        }

        foreach ($numbers as $key => $max) {

            if($key==0) {
                $min=1;
            } else {
                $min=$numbers[$key-1]+1;
            }

            $parcour=Parcour::whereHas('matieres', function ($query) {
                $query->where('semestre_id', 1);
            })->get()->random();

            $data=$this->fakeEtudiant($parcour->id, $min, $max,false);

            Personne::insert($data['personne']);
            Etudiant::insert($data['etudiant']);
            Historique::insert($data['historique']);

            foreach ($data['historique'] as $hist) {
                $historique=Historique::with('parcour')->find($hist['id']);
                $this->createDefaultNotePassant($historique->parcour,$historique);
            }

            $this->calcProgress($key, count($numbers));

            $this->calculMoyenneUpdate(Note::count());
        }


    }

    public function time_filter_notes(){

        $this->file_path = storage_path('app/nom_du_fichier.txt');

        $this->viderData();

        $numbers= [];//15000,20000,25000,30000,35000,40000,45000,50000,50000,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000];//,9000];//10000,20000];//,30000,40000,100000,1000000];
                

        for ($i=10; $i<=10000 ; $i+=10) {
            $numbers[]=$i;
        }


        foreach ($numbers as $key => $max) {

            if($key==0) {
                $min=1;
            } else {
                $min=$numbers[$key-1]+1;
            }

            $parcour=Parcour::whereHas('matieres', function ($query) {
                $query->where('semestre_id', 1);
            })->get()->random();

            $data=$this->fakeEtudiant($parcour->id, $min, $max);

            Personne::insert($data['personne']);
            Etudiant::insert($data['etudiant']);
            Historique::insert($data['historique']);
            Note::insert($data['note']);


            $this->calcProgress($key, count($numbers));

            $this->calculMoyenne(Note::count());
        }


    }

    private function calculMoyenneUpdate($n){
        

        $time1=[];

        for($i=1;$i<500;$i++){

            $historique=Historique::all()->random();
            $matiere=$historique->parcour->matieres->random();

            $time1[]=$this->countTimeUpdate($historique,$matiere);

        }

        $this->writeFile($n,round(collect($time1)->avg(),2));

    }

    private function calculMoyenne($n){

        $time1=[];
        $time2=[];

        for($i=1;$i<2000;$i++){

            $historique=Historique::all()->random();
            $parcour=Parcour::whereHas('matieres',function($query){
                $query->where('semestre_id',1);
            })->get()->random();
            

            $request=new Request;

            $request->merge([
                'numeroInscription'=>$historique->numeroInscription,
            ]);
            $time1[]=$this->countTime($request);

            $request=new Request;
            
            $request->merge([
                'parcours'=>[$parcour->id],
                'session_id'=>2,
                'semestre_id'=>2
            ]);

            $time2[]=$this->countTime($request);

        }
        
        $this->writeFile($n,round(collect($time1)->avg(),2),round(collect($time2)->avg(),2));
    }

    private function viderData(){
        Schema::disableForeignKeyConstraints();
        file_put_contents($this->file_path,'');
        Personne::truncate();
        Etudiant::truncate();
        Note::truncate();
        Historique::truncate();
        Schema::enableForeignKeyConstraints();
    }

    private function countTime($request){

        $filter= new FilterService;

        $filter->setQuery($request);

        //begin count
        $start = microtime(true);
        $filter->handle();
        $end = microtime(true);
        //end count

        $executionTimeMs = ($end - $start) * 1000;

        return $executionTimeMs;

    }

    private function countTimeUpdate(Historique $historique,Matiere $matiere){

        $MoyenneService=new MoyenneService;

        //begin count
        $start = microtime(true);
        $MoyenneService->calculateAll($matiere,$historique);
        $end = microtime(true);
        //end count

        $executionTimeMs = ($end - $start) * 1000;

        return $executionTimeMs;

    }

    private function writeFile($n,...$times){
        $tex=implode(' & ',$times);
        $data="\n$n".' & '.$tex.' \\\\ '."\n".'\hline';
        file_put_contents($this->file_path, $data, FILE_APPEND);
    }

    private function fakeEtudiant($parcour_id,$min,$max,$has_note=true){

        $faker = Factory::create();

        $data=[];

        $matieres=Parcour::find($parcour_id)->matieres()->where('semestre_id',1)->get();

        for ($i=$min; $i <= $max; $i++) { 

            $data['personne'][]=[
                'id'=>$i,
                'nom'=>strtoupper($faker->lastName),
                'prenom'=>$faker->firstName,
                'date_naissance'=>random_int(1980,2007)."-".random_int(1,12)."-".random_int(1,28),
                'lieu_naissance'=>$faker->city,
                'address'=>$faker->address,
                'cin'=>$faker->isbn10,
            ];

            $data['etudiant'][]=[
                'id'=>$i,
                'personne_id'=>$i
            ];

            $data['historique'][]=[
                'id'=>$i,
                'etudiant_id'=>$i,
                'parcour_id' =>$parcour_id,
                'numeroInscription' => $faker->ean13,
                'annee_universitaire_id' => 1,
                'status_id' => collect(['1','2'])->random()
            ];

            if($has_note){
                foreach ($matieres as $matiere) {
                    $data['note'][]=[
                        'historique_id'=> $i,
                        'matiere_id'    => $matiere->id,
                        'session_id' => 2,
                        'semestre_id'=> 2,
                        'is_set'     => 1,
                        'valeur'     => random_int(0,20) 
                    ];
                }
            }



        }

        return $data;

    }

    private function calcProgress($key,$count){
        $percent=round(((int)$key/($count-1))*100,0);
        $percent=intval($percent);
        $this->messageOneLine("progress->$percent%");
    }
    private function messageOneLine($message){

        $this->output->write("\r $message");
    }

    private function createDefaultNotePassant(Parcour $parcour,Historique $historique){

        foreach (Session::all() as $session) {

            foreach ($parcour->matieres as $matiere) {
                Note::create([
                    'historique_id'=>$historique->id,
                    'matiere_id'   => $matiere->id,
                    'semestre_id'  => $matiere->semestre_id,
                    'session_id'   => $session->id,
                    'valeur'       =>random_int(0,20),
                    'is_set'       =>1
                ]);

            }

        }

        foreach ($parcour->matieres as $matiere) {

            MoyenneMatiere::create([
                'valeur'        =>0,
                'matiere_id'    =>$matiere->id,
                'historique_id' =>$historique->id,
                'default_coefficient'   => $matiere->coefficient
            ]);

            if($matiere->tp)
                NoteTp::create([
                    'tp_id' =>$matiere->tp->id,
                    'historique_id' => $historique->id
                ]);

        }


        foreach (Semestre::all() as $semestre) {

            $ues=Ue::whereHas('matieres',function($query) use ($parcour,$semestre){
                $query->where('parcour_id',$parcour->id)->where('semestre_id',$semestre->id);
            })->get();

            $ues->each(function ($items) use ($semestre,$historique){

                MoyenneUe::create([
                    'valeur'                => 0,
                    'ue_id'                 => $items->id,
                    'semestre_id'           => $semestre->id,
                    'historique_id'         => $historique->id,
                    'default_credit'        => $items->credit,
                    'default_coefficient'   => $items->coefficient
                ]);

            });

        }


        foreach (Semestre::all() as $semestre) {

            MoyenneSemestre::create([
                'valeur'        =>0,
                'semestre_id'   =>$semestre->id,
                'historique_id' =>$historique->id
            ]);
            
        }


        MoyenneAnnee::create([
            'valeur'        => 0,
            'historique_id' => $historique->id
        ]);


    }

}

