<?php

namespace App\Service\Moyenne;

use App\Models\Note;
use App\Models\Matiere;
use App\Models\Parcour;
use App\Models\Semestre;
use App\Models\MoyenneUe;
use App\Models\Historique;
use App\Jobs\calculMoyenneJob;
use App\Models\MoyenneMatiere;
use Illuminate\Support\Facades\Artisan;


/**
 *  class responsable de calcul moyenne
 */

class MoyenneService implements MoyenneFormule{


    public function updateMoyenneMatiere(
        Matiere $matiere,
        Historique $historique
    ){

        $valeur=$historique->notes()
            ->where('matiere_id',$matiere->id)
            ->where('semestre_id',$matiere->semestre->id)
            ->get()
            ->max('valeur');

        if($matiere->tp){
            $valeur=$this->getMoyenneTp($matiere,$historique,$valeur);
        }
        

        return $historique->moyenne_matieres()
            ->where('matiere_id',$matiere->id)
            ->whereHas('matiere',function($query) use ($matiere){
                $query->where('matieres.semestre_id',$matiere->semestre->id)->whereStatus(1);
            })
            ->update([
            'valeur'=>$valeur
            ]);


    }

    public function getMoyenneTp(Matiere $matiere,Historique $historique,$moyenne_matiere){
        
        $note_tp=$historique->note_tps()->whereHas('tp',function($query) use($matiere){
            $query->where('matiere_id',$matiere->id);
        })->first();

        //le calcul est un peut exception
        //coefficient du tp = 1
        //coefficient moyenne exam=2
        $valeur=round((($note_tp->valeur + $moyenne_matiere * 2)/3),2);

        return $valeur;

    }


    public function UpdateMoyenneUes(
        Matiere $matiere,
        Historique $historique
    )
    {

        $semestre=$matiere->semestre;

        $ue=$matiere->ue;

        $moyenne_matieres=$ue->matieres()
            ->whereStatus(1)
            ->with(['moyenne_matieres'=>function($query) use ($historique){
                $query->where('historique_id',$historique->id);
            }])
            ->whereHas('parcour',function($matiere_query) use ($historique){
                $matiere_query->where('parcour_id',$historique->parcour_id);
            })
            ->where('semestre_id',$semestre->id)
            ->get();


        $total_note=0;
        $total_coefficient=0;

            
        $moyenne_matieres->each(
            function($items,$key) use(&$total_note,&$total_coefficient)
            {
                /**
                 *  le coefficient du matiere n'est pas une coefficient normal, 
                 *  c'est une credit de la matiere
                 *  donc il faut diviser par 2 pour obtenir le coefficient
                 */
                $total_note+=$items->moyenne_matieres->first()->valeur*($items->moyenne_matieres->first()->default_coefficient/2); 
                $total_coefficient+=($items->moyenne_matieres->first()->default_coefficient/2);

            });



        if($total_coefficient==0){
            abort(400, "total coefficient ne peut pas etre zero line ".__LINE__);
        }
    
        
        $valeur=(float)($total_note/$total_coefficient);

        if(enCours()->session->id==2){ //si session normal
            $data_moyenne_ue=[
                'credit'=> (round($valeur,2) >= 10) ? $historique->moyenne_ues()->where('ue_id',$matiere->ue->id)->first()->default_credit: 0,
                'valeur'=>round($valeur,2),
                'valeur_session_normal' => round($valeur,2)
            ];
        }else{  //resultat final
            $data_moyenne_ue=[
                'credit'=> (round($valeur,2) >= 10) ? $historique->moyenne_ues()->where('ue_id',$matiere->ue->id)->first()->default_credit: 0,
                'valeur'=>round($valeur,2),
            ];
        }

        return $historique->moyenne_ues()
            ->where('semestre_id',$semestre->id)
            ->where('ue_id',$ue->id)
            ->update($data_moyenne_ue);

    }





    public function updateMoyenneSemestre(
        Historique $historique,
        $semestre_id=1 
    )
    {


        $semestre=Semestre::find($semestre_id);

        


        $moyenne_ues = $historique->moyenne_ues()
            ->with(['ue','ue.parcour'])
            ->where('semestre_id',$semestre->id)
            ->get()
            ->groupBy('ue.option');


        $total_moyenne_ues  = 0;
        $total_coefficients = 0;
        $total_credit       = 0;
        $total_ue_valide    = 0;

        foreach ($moyenne_ues as $option => $moyenne_ue_lists) {

            if($option){ //si optionel


                /**
                 *  pour le ue selectionner par etudiant
                 */

                 $ue_choisit=$historique->ue_options->map(function($item){
                    return $item->ue_id;
                 })->toArray();

                 if(!empty($ue_choisit)){

                    $moyenne_ues_choix=$moyenne_ue_lists->whereIn('ue_id',$ue_choisit);

                    foreach ($moyenne_ues_choix as $key => $moyenne_ue) {

                        $total_moyenne_ues+=$moyenne_ue->valeur*$moyenne_ue->default_coefficient;
                        $total_coefficients+=$moyenne_ue->default_coefficient;
                        $total_credit+=$moyenne_ue->credit;
                        if($moyenne_ue->valeur>=10){
                            $total_ue_valide+=1;
                        }

                    }
            
                    continue;
                 }


                /**
                 *  calcul pour la partie optionel avec nombre d'ue obli
                 */

                $nombre_ue_option_obli=$historique->parcour
                                        ->nombre_ue_option_obligatoires()
                                        ->where('semestre_id',enCours()->semestre->id)
                                        ->first();

                if(!$nombre_ue_option_obli)
                    continue;

                $moyenne_ues_max_take=$moyenne_ue_lists->sortByDesc('valeur')->take($nombre_ue_option_obli->nombre_ue_obli);

                foreach ($moyenne_ues_max_take as $key => $moyenne_ue) {

                    $total_moyenne_ues+=$moyenne_ue->valeur*$moyenne_ue->default_coefficient;
                    $total_coefficients+=$moyenne_ue->default_coefficient;
                    $total_credit+=$moyenne_ue->credit;
                    if($moyenne_ue->valeur>=10){
                        $total_ue_valide+=1;
                    }

                }

            }else{  //si obligatoire

                foreach ($moyenne_ue_lists as $key => $moyenne_ue) {

                    $total_moyenne_ues+=$moyenne_ue->valeur*$moyenne_ue->default_coefficient;
                    $total_coefficients+=$moyenne_ue->default_coefficient;
                    $total_credit+=$moyenne_ue->credit;
                    if($moyenne_ue->valeur>=10){
                        $total_ue_valide+=1;
                    }

                }

            }

        }


        if(!$total_coefficients){
            if($semestre_id==1)
                return $this->updateMoyenneSemestre($historique,2);
            else
                return true;
        }

        $valeur= (float)($total_moyenne_ues/$total_coefficients);

        $historique->moyenne_semestres()
            ->where('semestre_id',$semestre->id)
            ->update([
                'valeur'          => round($valeur,2),
                'total_credit'    =>$total_credit,
                'total_ue_valide' => $total_ue_valide
            ]);

        if($semestre_id==1)
            return $this->updateMoyenneSemestre(Historique::find($historique->id),2);
        else
            return true;

    }


    public function updateMoyenneAnnee(
        Historique $historique
    )
    {

        $moyenne_ues = $historique->moyenne_ues()->with('ue')->get();

        $total_credit=0;
        $tatal_ue_valide=0;
        $moyenne_general=0;

        foreach ($moyenne_ues as $key => $moyenne_ue) {
            $total_credit+=$moyenne_ue->credit;
        }


        
        foreach ($historique->moyenne_semestres as $key => $moyenne_semestre) {
            $moyenne_general+=$moyenne_semestre->valeur;
        }
        
        $valeur=$moyenne_general/2;


        $historique->moyenne_annee()
                ->update([
                    'valeur'       => round($valeur,2),
                    'total_credit' => $total_credit
                ]);

        $historiqueID= $historique->id;

        Artisan::call("moyenne-resolution:pour-un-etudiant $historiqueID");
        Artisan::call("set-critere-admis:for-etudiant $historiqueID");

        return true;

    }


    public function updateMoyenne(Matiere $matiere,Historique $historique,int $semestre_id=1){

        calculMoyenneJob::dispatch($matiere->id,$historique->id,$semestre_id);

    }

    public function calculateAll(Matiere $matiere,Historique $historique,int $semestre_id=1){

        $this->updateMoyenneMatiere($matiere,$historique);
        $this->UpdateMoyenneUes($matiere,$historique);
        $this->updateMoyenneSemestre($historique,$semestre_id);
        $this->updateMoyenneAnnee($historique);

    }


    public function validateUeOption(Matiere $matiere,Historique $historique)
    {


        $choisit=$historique->ue_options()->where('ue_id',$matiere->ue->id)->first();
        $option=$matiere->parcour->ues()->where('semestre_id',$matiere->semestre_id)->where('option',1)->first();


        if(!$option){
            return ;
        }

        $nombre_ue_option_obli=$matiere->parcour
                        ->nombre_ue_option_obligatoires()
                        ->where('semestre_id',$matiere->semestre->id)
                        ->first();

        if(!$nombre_ue_option_obli && !$choisit){
            abort(400,"Veuillez ajouter le nombre d'UE optionnel qu'il faudra prendre en compte dans le calcul de la moyenne.");
        }
    }


}
