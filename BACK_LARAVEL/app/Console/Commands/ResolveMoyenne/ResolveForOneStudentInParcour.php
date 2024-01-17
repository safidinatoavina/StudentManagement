<?php

namespace App\Console\Commands\ResolveMoyenne;

use App\Models\Historique;
use Illuminate\Console\Command;
use App\Models\CritereValidation;

class ResolveForOneStudentInParcour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moyenne-resolution:pour-un-etudiant {historique}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "resoudre la table moyenne_annees et moyenne_semestres pour un étudiant selon le critere";

    /**
     * Create a new command instance.
     *
     * @return void
     */

     public $historique;

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

        $historiqueID = $this->argument('historique');

        if(!$historiqueID){
            abort(400,"historique n'est pas mentioner");
        }

        $this->historique=Historique::findOrFail($historiqueID);

        $this->updateMoyenneSemestreCommand();
        $this->updateMoyenneAnneeCommand();
        
        return 0;
    }

    private function updateMoyenneSemestreCommand()
    {
 

        //TODO: critere de validation par semestre

        $validation=$this->getValidation($this->historique);

        $this->historique->moyenne_semestres()
            ->where('semestre_id',enCours()->semestre->id)
            ->update([
                'validation' => $validation,
                'is_valide'  => strtolower($validation)=='nv'?0:1
            ]);

    }


    private function updateMoyenneAnneeCommand()
    {

        $total_credit=0;
        $total_ue_valide=0;

        $moyenne_semestres=$this->historique->moyenne_semestres;

        foreach ($moyenne_semestres as $key => $moyenne) {
            $total_credit+=$moyenne->total_credit;
            $total_ue_valide+=$moyenne->total_ue_valide;
        }


        $this->historique->moyenne_annee->update([
            'total_credit'      => $total_credit,
            'total_ue_valide'   => $total_ue_valide
        ]);

    }


    private function getValidation(Historique $historique,$type='v')
    {
        $moyenne_semestre=$historique->moyenne_semestres()
                                    ->where('semestre_id',enCours()->semestre->id)
                                    ->first();

        $critere_semestre=$historique->parcour->critere_validations()
                                    ->where('annee_universitaire_id',annee()->id)
                                    ->where('semestre_id',enCours()->semestre->id)
                                    ->where('type',$type)
                                    ->first();
        if(!$critere_semestre)
            return 'NV';
    
        if(strtolower($critere_semestre->type)=='v'){ //type v
            if($critere_semestre->logique=='et'){
                if(
                    $moyenne_semestre->valeur >= $critere_semestre->min_moyenne 
                    && 
                    $moyenne_semestre->total_ue_valide >= $critere_semestre->min_ue
                    &&
                    $moyenne_semestre->total_credit    >= $critere_semestre->min_credit
                ){

                    return 'V';
    
                }else{
                    return $this->getValidation($historique,'vpc');
                }
            }else{
                if(
                    $moyenne_semestre->valeur >= $critere_semestre->min_moyenne 
                    || 
                    $moyenne_semestre->total_ue_valide >= $critere_semestre->min_ue
                    ||
                    $moyenne_semestre->total_credit    >= $critere_semestre->min_credit
                ){

                    return 'V';
    
                }else{
                    return $this->getValidation($historique,'vpc');
                }
            }
        }else{ //type VPC

            if($critere_semestre->logique=='et'){
                if(
                    $moyenne_semestre->valeur >= $critere_semestre->min_moyenne 
                    && 
                    $moyenne_semestre->valeur >= $critere_semestre->max_moyenne 
                    &&
                    $moyenne_semestre->total_ue_valide >= $critere_semestre->min_ue
                    &&
                    $moyenne_semestre->total_ue_valide >= $critere_semestre->max_ue
                    &&
                    $moyenne_semestre->total_credit    >= $critere_semestre->min_credit
                    &&
                    $moyenne_semestre->total_credit    >= $critere_semestre->max_credit
                ){

                    return 'VPC';
    
                }else{
                    return 'NV';
                }
            }else{
                if(
                    $moyenne_semestre->valeur >= $critere_semestre->min_moyenne 
                    || 
                    $moyenne_semestre->valeur >= $critere_semestre->max_moyenne 
                    ||
                    $moyenne_semestre->total_ue_valide >= $critere_semestre->min_ue
                    ||
                    $moyenne_semestre->total_ue_valide >= $critere_semestre->max_ue
                    ||
                    $moyenne_semestre->total_credit    >= $critere_semestre->min_credit
                    ||
                    $moyenne_semestre->total_credit    >= $critere_semestre->max_credit
                ){

                    return 'VPC';
    
                }else{
                    return 'NV';
                }
            }

        }

    }

}
