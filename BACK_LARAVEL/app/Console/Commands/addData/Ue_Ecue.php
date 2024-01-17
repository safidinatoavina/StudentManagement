<?php

namespace App\Console\Commands\addData;

use App\Models\TP;
use App\Models\Ue;
use App\Models\NoteTp;
use App\Models\Matiere;
use App\Models\Parcour;
use Illuminate\Console\Command;
use App\Service\Moyenne\MoyenneService;
use App\Http\Controllers\data_faculte\DataFaculteController;

class Ue_Ecue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:ue-ecue-tp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy parcour ecue avec ue et tp';

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

        $abrev_parcour_from='CM-M1';
        $abrev_parcours_to=['CE-M1','PCMC-M1','2GE-M1','CAQ-M1','Chimie Bio-M1','Chimie Mol-M1','CIGP-M1','CSN-M1'];
        $ues_id=[472,475,761];

        $this->verifyData($abrev_parcours_to);

        $parcour_from=Parcour::with(['ues','ues.matieres','ues.matieres.tp'])->where('abreviation',$abrev_parcour_from)->first();

        if(!$parcour_from){
            abort(400,"\nparcour exemplaire introuvable\n");
        }

        $this->copyUE($parcour_from,$abrev_parcours_to,$ues_id);

        return 0;
    }

    public function verifyData($abrev_parcours_to)
    {
        foreach ($abrev_parcours_to as $key => $abbrev) {
            $parcour_to_copy=Parcour::where('abreviation',$abbrev)->first();
            if(!$parcour_to_copy){
                dd("parcour abreviation ($abbrev) est introuvable");
            }
        }
    }

    public function copyUE(Parcour $parcour_from,$abrev_parcours_to,$ues_id){


        $MoyenneService=new MoyenneService;

        $selected_ues=$parcour_from->ues()->whereIn('ues.id',$ues_id)->get();

        foreach ($abrev_parcours_to as $key => $abrev_parcour_cp) {

            $parcour_to_copy=Parcour::where('abreviation',$abrev_parcour_cp)->first();

            if(!$parcour_to_copy){
                echo("\nParcour ($abrev_parcour_cp) est introuvable\n");
                continue;
            }

            foreach ($selected_ues as $ue) {


                $parcour_to_copy_ue=$parcour_to_copy->ues()->where('ues.ue',$ue->ue)->first();

                if($parcour_to_copy_ue){
                    $ue_created=$parcour_to_copy_ue;
                }else{
                    $ue_created=Ue::create([
                        'parcour_id'   => $parcour_to_copy->id,
                        'ue'           => $ue->ue,
                        'option'       =>$ue->option,
                        'semestre_id'  =>$ue->semestre_id,
                        'coefficient'  =>$ue->coefficient,
                        'credit'       =>$ue->credit
                    ]);
                }


                $this->copyECUE($ue,$ue_created,$parcour_to_copy);


            }


            


            foreach ($parcour_to_copy->historiques as $historique) {
                foreach ($parcour_to_copy->matieres as $matiere) {
                    $MoyenneService->updateMoyenne($matiere,$historique);
                }
            }

            $this->calcProgress($key,count($abrev_parcours_to));

        }

        echo("\n");



    }

    public function copyECUE(Ue $ue,Ue $ue_created,Parcour $parcour_to_copy){

        $DataFaculteController=new DataFaculteController;
        
        foreach ($ue->matieres as $matiere) {


            $ue_to_copy_matiere=$ue_created->matieres()->where('matiere',$matiere->matiere)->first();

            if($ue_to_copy_matiere){
                $matiere_created=$ue_to_copy_matiere;
            }else{
                $matiere_created=Matiere::create([
                    'matiere'    => $matiere->matiere,
                    'parcour_id' => $parcour_to_copy->id,
                    'user_id'    => $matiere->user_id,
                    'ue_id'      => $ue_created->id,
                    'semestre_id'=> $ue_created->semestre_id,
                    'coefficient'=> $matiere->coefficient,
                    'status'     => 1
                ]);

                $DataFaculteController->createDefaultNote($parcour_to_copy,Matiere::find($matiere_created->id));
            }




            if($matiere->tp){
                $this->copyTP($parcour_to_copy,$matiere,$matiere_created);
            }

            
        }
    }

    public function copyTP(Parcour $parcour_to_copy, Matiere $matiere,Matiere $matiere_created ){


        if($matiere_created->tp)
            return;

        $tp=TP::create([
            'matiere_id'  => $matiere_created->id,
            'tp'          => $matiere->tp->tp,
            'user_id'     => $matiere->tp->user_id,
        ]);

        foreach ($parcour_to_copy->historiques as $historique) {
            NoteTp::create([
                'tp_id' =>$tp->id,
                'historique_id' => $historique->id
            ]);
        }


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
