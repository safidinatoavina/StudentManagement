<?php

namespace App\Service;

use Exception;
use App\Models\EnCour;
use App\Models\AnneeUniversitaire;


class DefaultData{

    public function getEnCours(){

        $en_cours=EnCour::first();

        if($en_cours){
            return $en_cours;
        }
        else{
            abort(400,"la table 'en_cours' est encore vide");
        }
    }


    public function getAnnee(){

        $annee=AnneeUniversitaire::where('statut',1)->orderBy('id','desc')->first();

        if($annee){
            return $annee;
        }else{
            abort(403,"Année univérsitaire n'est pas definie dans la table 'annee_universitaires'");
        }


    }

}

