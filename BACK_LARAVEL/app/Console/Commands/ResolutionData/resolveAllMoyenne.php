<?php

namespace App\Console\Commands\ResolutionData;

use App\Models\Ue;
use App\Models\Matiere;
use App\Models\Parcour;
use Illuminate\Console\Command;
use App\Service\Moyenne\MoyenneService;

class resolveAllMoyenne extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resolve:all-moyenne';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'resolution de tous les moyennes description';

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
        

        echo("\n");
        
        echo(now());
        
        $this->resolveAll();

        echo(now());


        echo("\n");

        return 0;
    }

    public function resolveAll(){

        $parcours=Parcour::all();
        $MoyenneService=new MoyenneService;
        $count=$parcours->count();

        echo("\nDebut résolution Moyenne\n");

        foreach ($parcours as $key => $parcour) {

            $matieres=$parcour->matieres;
            $historiques=$parcour->historiques;
            
            $this->calcProgress($key,$count);

            foreach ($matieres as $k=> $matiere) {
                foreach ($historiques as $i => $historique) {
                    $MoyenneService->calculateAll($matiere,$historique);
                }
            }
        }

        echo("\nFin résolution Moyenne\n");


    }

    public function verifyUEMAtiere(){


        $matieres=Matiere::with('ue')->get();
        $count=$matieres->count();

        echo("\n VERIFICATION PARCOUR DE UE ET MATIERE\n");

        foreach ($matieres as $key => $matiere) {

            $this->calcProgress($key,$count);

            if($matiere->ue->parcour_id!=$matiere->parcour_id)
                echo("-".$matiere->id.' '.$matiere->parcour_id.' '.$matiere->ue->id.' '.$matiere->ue->parcour_id."\n");


        }

        echo("\nFin verification \n");

    }

    public function resolveSemestreNote(){

        $parcours=Parcour::all();
        $count=$parcours->count();

        echo("\nDebut résolution semestre\n");

        foreach ($parcours as $key => $parcour) {

            $matieres=$parcour->matieres;
            
            $this->calcProgress($key,$count);

            foreach ($matieres as $k=> $matiere) {
                
                $matiere->notes()->update(['semestre_id'=>$matiere->semestre_id]);

            }
        }

        echo("\nFin résolution semestre \n");

    }

    private function calcProgress($key,$count){
        $key=$key+1;
        $percent=round(((int)$key/($count))*100,0);
        $percent=intval($percent);
        $this->messageOneLine("progress --> ".$percent."%");
    }

    private function messageOneLine($message){

        $this->output->write("\r $message");
    }


}
