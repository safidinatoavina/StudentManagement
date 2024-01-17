<?php

namespace App\Console\Commands\ApplyCritereAdmis;

use App\Models\Historique;
use Illuminate\Console\Command;

class setCritereForOneStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-critere-admis:for-etudiant {historique}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command pour appiquer le critere de passage à niveau pour un etudiant';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $historique=0;

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

        $this->historique=Historique::find($this->argument('historique'));

        if(!$this->historique)
            abort(400,"historique introuvable sur l'application de critere");
        
        //mis a ajour de son status 
        $this->historique->moyenne_annee->update(['is_admis'=>$this->is_admis()]);

        return 0;
    }

    private function is_admis($type='passant')
    {
        $critere_admis=annee()->critere_admis()->where('type',$type)->first();
        $moyenne_annee=$this->historique->moyenne_annee;

        $semestre_paire=$this->historique->moyenne_semestres()->where('semestre_id',1)->first();
        $semestre_impaire=$this->historique->moyenne_semestres()->where('semestre_id',2)->first();

        //voir si les deux semestre sont valide (admis)
        if((bool)$semestre_paire->is_valide && (bool)$semestre_impaire->is_valide)
            return 1;

        if(!$critere_admis){
            return 0;
        }
        else{

            //start
            if(strtolower($critere_admis->type)=='passant'){ //type 
                if($critere_admis->logique=='et'){
                    if(
                        $moyenne_annee->valeur          >= $critere_admis->min_moyenne 
                        && 
                        $moyenne_annee->total_ue_valide >= $critere_admis->min_ue
                        &&
                        $moyenne_annee->total_credit    >= $critere_admis->min_credit
                    ){
    
                        return 1; //passant
        
                    }else{
                        return $this->is_admis('redoublant'); 
                    }
                }else{
                    if(
                        $moyenne_annee->valeur          >= $critere_admis->min_moyenne 
                        || 
                        $moyenne_annee->total_ue_valide >= $critere_admis->min_ue
                        ||
                        $moyenne_annee->total_credit    >= $critere_admis->min_credit
                    ){
    
                        return 1; //passant
        
                    }else{
                        return $this->is_admis('redoublant');
                    }
                }
            }else{ //type redoublant
    
                if($critere_admis->logique=='et'){
                    if(
                        $moyenne_annee->valeur          >= $critere_admis->min_moyenne 
                        && 
                        $moyenne_annee->valeur          <= $critere_admis->max_moyenne 
                        &&
                        $moyenne_annee->total_ue_valide >= $critere_admis->min_ue
                        &&
                        $moyenne_annee->total_ue_valide <= $critere_admis->max_ue
                        &&
                        $moyenne_annee->total_credit    >= $critere_admis->min_credit
                        &&
                        $moyenne_annee->total_credit    <= $critere_admis->max_credit
                    ){
                        return 0; //redoublant
        
                    }else{
                        return -1; //renvoyé
                    }
                }else{
                    if(
                        (
                        $moyenne_annee->valeur >= $critere_admis->min_moyenne 
                        &&
                        $moyenne_annee->valeur <= $critere_admis->max_moyenne 
                        )
                        ||
                        (  
                        $moyenne_annee->total_ue_valide >= $critere_admis->min_ue
                        &&
                        $moyenne_annee->total_ue_valide <= $critere_admis->max_ue
                        )
                        ||
                        (
                        $moyenne_annee->total_credit    >= $critere_admis->min_credit
                        &&
                        $moyenne_annee->total_credit    <= $critere_semestre->max_credit
                        )
                    ){
                        return 0;//redoublant
        
                    }else{
                        return -1;//renvoyé
                    }
                }
    
            }
            //end

        }

    }
}
