<?php

namespace App\Service;

use App\Models\Note;
use App\Models\Status;
use App\Models\Matiere;
use App\Models\Parcour;
use App\Models\Etudiant;
use App\Models\Personne;
use App\Models\Historique;
use App\Models\AnneeUniversitaire;

class DataService
{
    /**
     *  
     * Methode de simulation des données
     * 
     */

    public static function createHistorique()
    {
        $parcours_id=Parcour::all()->map(function ($parc){
            return $parc->id;
        })->toArray();

        $annee_universitaires_id=AnneeUniversitaire::all()->map(function ($anne){
            return $anne->id;
        })->toArray();

        $status_id=Status::all()->map(function ($statu){
            return $statu->id;
        })->toArray();

        $matiers_id=Matiere::all()->map(function ($matier){
            return $matier->id;
        })->toArray();

        $personne=Personne::factory(1)->create()->first();
        
        $parcour=Parcour::find($parcours_id[array_rand($parcours_id,1)]);
        $annee=AnneeUniversitaire::find($annee_universitaires_id[array_rand($annee_universitaires_id,1)]);
        $status=Status::find($status_id[array_rand($status_id,1)]);
        $etudiant=Etudiant::create(['personne_id'=>$personne->id]);

        $historique=Historique::create([
            'etudiant_id'               =>$etudiant->id,
            'parcour_id'                =>$parcour->id,
            'annee_universitaire_id'    => $annee->id,
            'status_id'                 => $status->id,
            'numeroInscription'         => random_int(2110050000,9110060000)
        ]);

        $matiere_id=Matiere::find($matiers_id[array_rand($matiers_id,1)]);

        $note=Note::create([
            'historique_id'=>$historique->id,
            'session_id'   => 1,
            'semestre_id'  =>$matiere_id->semestre_id,
            'matiere_id'   => $matiere_id->id,
            'valeur'   => random_int(0,20),
        ]);

        $note=Note::create([
            'historique_id'=>$historique->id,
            'session_id'   => 2,
            'semestre_id'  =>$matiere_id->semestre_id,
            'matiere_id'   => $matiere_id->id,
            'valeur'   => random_int(0,20),
        ]);

        
        return $historique;

    }

}
