<?php

namespace App\Console\Commands\Resolution;

use App\Models\Ue;
use App\Models\Matiere;
use Illuminate\Console\Command;
use App\Service\Moyenne\MoyenneService;

class ResolveCoeffAndCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resolve-coef-credit:matiere-ue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'resolution des coefficient modifié';

    public $MoyenneService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->MoyenneService=new MoyenneService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->resolve_moyenne_ues();
        
        $this->resolve_moyenne_matieres();

        return 0;
    }

    public function resolve_moyenne_matieres(){

        $matieres=Matiere::with(['moyenne_matieres'=>function($query){
            $query->whereHas('historique',function($historique_query){
                $historique_query->where('annee_universitaire_id',annee()->id);
            });
        },'parcour'])->get();


        foreach ($matieres as $key => $matiere) {

            $moyenne_matiere=$matiere->moyenne_matieres->first();
            if($moyenne_matiere){
                if($matiere->coefficient!=$moyenne_matiere->default_coefficient){
                    echo("ECUE : ".$matiere->matiere." parcour : ".$matiere->parcour->parcour." \n");
                    $matiere->moyenne_matieres()->update(['default_coefficient'=>$matiere->coefficient]);
                    $historiques=$matiere->parcour->historiques;

                    foreach ($historiques as $key => $historique) {
                        $this->MoyenneService->updateMoyenne($matiere,$historique);
                    }

                }
            }
            
        }

    }


    public function resolve_moyenne_ues(){

        $ues=Ue::with(['moyenne_ues','parcour'])->get();

        foreach ($ues as $key => $ue) {

            $moyenne_ue=$ue->moyenne_ues->first();

            if($moyenne_ue){
                if($ue->coefficient!=$moyenne_ue->default_coefficient){
                    echo("coefficient --> ue : ".$ue->ue." parcour : ".$ue->parcour->parcour." \n");
                    $ue->moyenne_ues()->update(['default_coefficient'=>$ue->coefficient]);

                    $historiques=$ue->parcour->historiques;

                    foreach ($historiques as $key => $historique) {
                        foreach ($ue->matieres as $key => $matiere) {
                            $this->MoyenneService->updateMoyenne($matiere,$historique);
                        }
                    }

                }
                if($ue->credit!=$moyenne_ue->default_credit){
                    echo("credit --> ue : ".$ue->ue." parcour : ".$ue->parcour->parcour." \n");
                    $ue->moyenne_ues()->update(['default_credit'=>$ue->credit]);

                    $historiques=$ue->parcour->historiques;

                    foreach ($historiques as $key => $historique) {
                        foreach ($ue->matieres as $key => $matiere) {
                            $this->MoyenneService->updateMoyenne($matiere,$historique);
                        }
                    }
                    
                }
            }
        }

    }

}
