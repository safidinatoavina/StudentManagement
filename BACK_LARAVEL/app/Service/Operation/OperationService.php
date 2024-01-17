<?php

namespace App\Service\Operation;

use App\Models\Ue;
use App\Models\Parcour;
use App\Models\Semestre;

class OperationService
{


    public function getListMoyenneParMatiere(Parcour $parcour, Semestre $semestre){

        $etudiants_moyenne_matiere=$parcour->historiques()
        ->with([
            'etudiant',
            'etudiant.personne',
            'moyenne_matieres'=>function($moyenne_matieres) use ($semestre){
                $moyenne_matieres->whereHas('matiere',function($query) use($semestre){
                    $query->whereHas('semestre',function($semestre_query) use ($semestre){
                        $semestre_query->where('semestres.id',$semestre->id);
                    });
                })->with('matiere');
            }
        ])
        ->get();

    
        $result=[];

        $etudiants_moyenne_matiere->each(function($historique) use (&$result){

            foreach ($historique->moyenne_matieres as $moyenne) {

                $data=[];
                $data['nom']=$historique->etudiant->personne->nom;
                $data['prenom']=$historique->etudiant->personne->prenom;
                $data['historique_id']=$historique->id;
                $data['numeroInscription']=$historique->numeroInscription;
                $data['matiere']=$moyenne->matiere->matiere;
                $data['moyenne']=$moyenne->valeur;
                $result[]=$data;

            }


        });

        return $result;

    }


    public function getListMoyenneUeParSemestre(Parcour $parcour, Semestre $semestre){

        $etudiants_moyenne_ue=$parcour->historiques()

        ->with([
                'etudiant',
                'etudiant.personne',
                'moyenne_ues'=>function($moyenne_ues) use ($semestre){
                    $moyenne_ues->whereHas('semestre',function($query) use($semestre){
                        $query->where('semestres.id',$semestre->id);
                    })->with('ue');
                }
            ])
            ->get();

        
        $result=[];

        $etudiants_moyenne_ue->each(function($historique) use (&$result){

            foreach ($historique->moyenne_ues as $moyenne) {

                $data=[];
                $data['nom']=$historique->etudiant->personne->nom;
                $data['prenom']=$historique->etudiant->personne->prenom;
                $data['historique_id']=$historique->id;
                $data['numeroInscription']=$historique->numeroInscription;
                $data['ue']=$moyenne->ue->ue;
                $data['moyenne']=$moyenne->valeur;
                $result[]=$data;

            }


        });

        return $result;

    }


    public function getListMoyenneParSemestre(Parcour $parcour, Semestre $semestre)
    {


        $etudiants_moyenne_semestre=$parcour->historiques()
                    ->with([
                        'etudiant',
                        'etudiant.personne',
                    'moyenne_semestres'=>function($moyennes) use ($semestre){
                        $moyennes->where('semestre_id',$semestre->id)->with('semestre');
                    },
                ])->get()
                ->sortBy([
                    ['etudiant.personne.nom','asc'],
                    ['etudiant.personne.prenom','asc'],
                ]);

        $result=[];

        $etudiants_moyenne_semestre->each(function($historique) use (&$result){

            $data=[];
            $data['nom']=$historique->etudiant->personne->nom;
            $data['prenom']=$historique->etudiant->personne->prenom;
            $data['historique_id']=$historique->id;
            $data['numeroInscription']=$historique->numeroInscription;
            $data['moyenne']=$historique->moyenne_semestres->first()->valeur;
            $data['validation']=$historique->moyenne_semestres->first()->validation;
            $data['semestre']=$historique->moyenne_semestres->first()->semestre->semestre;
            $data['total_ue_valide']=$historique->moyenne_semestres->first()->total_ue_valide;
            $data['total_credit']=$historique->moyenne_semestres->first()->total_credit;

            $result[]=$data;

        });

        return $result;


    }

    public function getListMoyenneDefinitive(Parcour $parcour){


        $etudiants_moyenne_definitive=$parcour->historiques()
            ->with([
                'etudiant',
                'etudiant.personne',
                'moyenne_annee'
            ]);


        $result=[];

        $etudiants_moyenne_definitive->each(function($historique) use (&$result){

            $data=[];
            $data['nom']=$historique->etudiant->personne->nom;
            $data['prenom']=$historique->etudiant->personne->prenom;
            $data['historique_id']=$historique->id;
            $data['numeroInscription']=$historique->numeroInscription;
            $data['moyenne']=$historique->moyenne_annee->valeur;

            $result[]=$data;

        });


        return $result;

    }
}


