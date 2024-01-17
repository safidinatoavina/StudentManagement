<?php

namespace App\Http\Controllers\Etudiant;

use App\Models\Parcour;
use App\Models\AnneeUniversitaire;
use Excel;
use App\Imports\EtudiantImport;

trait EtudiantTrait{


    public function getByParcours(Parcour $parcour,$anne=false){

        if($anne){
            $annee=AnneeUniversitaire::find($anne);
            if(!$annee){
                $annee=annee();
            }

            $result=$annee->historiques()
                          ->with(['etudiant','etudiant.personne','parcour','status'])
                          ->where('parcour_id',$parcour->id)
                          ->get()
                          ->sortBy([
                            ['etudiant.personne.nom','asc'],
                            ['etudiant.personne.prenom','asc']
                          ]);

        }else{

            $annee=annee();
            $result=$annee->historiques()
                          ->with(['etudiant','etudiant.personne','parcour','status'])
                          ->where('parcour_id',$parcour->id)
                          ->get()
                          ->sortBy([
                            ['etudiant.personne.nom','asc'],
                            ['etudiant.personne.prenom','asc']
                          ]);

        }

        $data=[];

        foreach ($result as $key => $value) {
            $data[]=$value;
        }

        return $data;


    }



    public function importEtudiant(){
        Excel::import(new EtudiantImport, storage_path('app/public/parcels.xlsx'));
    }

}


