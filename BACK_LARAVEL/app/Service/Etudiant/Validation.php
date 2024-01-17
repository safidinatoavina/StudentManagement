<?php

namespace App\Service\Etudiant;

use App\Models\TP;
use App\Models\Matiere;
use App\Models\Historique;


trait Validation{

    public function CanAddNote(Historique $historique,Matiere $matiere){

        $is_allow=$historique->notes()
            ->where('matiere_id',$matiere->id)
            ->where('session_id',enCours()->session_id)
            ->where('semestre_id',enCours()->semestre_id)
            ->whereIsSet(1)
            ->exists();


        return !(!!$is_allow);

    }

    public function CanAddNoteTP(Historique $historique,TP $tp){

        $is_allow=$historique->note_tps()
            ->whereHas('tp',function($query_tp) use($tp) {
                $query_tp->where('t_p_s.id',$tp->id)->whereHas('matiere',function($matiere_query){
                    $matiere_query->where('semestre_id',enCours()->semestre_id);
                });
            })
            ->whereIsSet(1)
            ->exists();


        return !(!!$is_allow);

    }

}

