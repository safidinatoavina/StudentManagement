<?php

namespace App\Console\Commands\Verification;

use App\Models\Ue;
use App\Models\Matiere;
use Illuminate\Console\Command;

class verifier_change_coefficient_annee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voir-les-coefficient-qui-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "verifier si quelequ'un a changer son coefficient";

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $this->verifier_moyenne_matieres();

        echo("------UE-------\n");

        $this->verifier_moyenne_ues();

        return 0;
    }


    public function verifier_moyenne_matieres(){

        $matieres=Matiere::with(['moyenne_matieres','parcour'])->get();


        foreach ($matieres as $key => $matiere) {

            $moyenne_matiere=$matiere->moyenne_matieres->first();
            if($moyenne_matiere){
                if($matiere->coefficient!=$moyenne_matiere->default_coefficient){
                    echo("ECUE : ".$matiere->matiere." parcour : ".$matiere->parcour->parcour." \n");
                }
            }
            
        }

    }

    public function verifier_moyenne_ues(){

        $ues=Ue::with(['moyenne_ues','parcour'])->get();

        foreach ($ues as $key => $ue) {

            $moyenne_ue=$ue->moyenne_ues->first();

            if($moyenne_ue){
                if($ue->coefficient!=$moyenne_ue->default_coefficient){
                    echo("coefficient --> ue : ".$ue->ue." parcour : ".$ue->parcour->parcour." \n");
                }
                if($ue->credit!=$moyenne_ue->default_credit){
                    echo("credit --> ue : ".$ue->ue." parcour : ".$ue->parcour->parcour." \n");
                }
            }
        }

    }

}
