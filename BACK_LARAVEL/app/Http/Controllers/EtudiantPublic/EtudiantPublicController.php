<?php

namespace App\Http\Controllers\EtudiantPublic;

use App\Models\Parcour;
use App\Models\Etudiant;
use App\Models\UePublic;
use App\Models\Historique;
use App\Models\PublicFinal;
use App\Models\CritereAdmis;
use Illuminate\Http\Request;
use App\Models\SemestrePublic;
use App\Models\AnneeUniversitaire;
use App\Http\Controllers\Controller;
use App\Service\Moyenne\MoyenneService;


class EtudiantPublicController extends Controller
{
    public function filter(Request $request){

        $request->validate([
            'annee_universitaire_id' => 'required'
        ]);

        $result=Historique::with(['etudiant.personne','parcour','status'])->orderBy('id','desc');


        if($request->numeroInscription){
            $result=$result->where('numeroInscription',$request->numeroInscription);
        }

        if($request->annee_universitaire_id){
            $result=$result->where('annee_universitaire_id',$request->annee_universitaire_id);
        }

        if($request->nom){
            $result=$result->whereHas('etudiant',function($etudiant) use ($request){
                $etudiant->whereHas('personne',function($personne) use ($request){
                    $personne->where('personnes.nom',$request->nom);
                });
            });
        }

        if($request->prenom){
            $result=$result->whereHas('etudiant',function($etudiant) use ($request){
                $etudiant->whereHas('personne',function($personne) use ($request){
                    $personne->where('personnes.prenom',$request->prenom);
                });
            });
        }

        return $result->get();

    }


    public function getAnneeList(){
        return AnneeUniversitaire::orderBy('id','desc')->get();
    }


    public function getHistoriqueList(Etudiant $etudiant){

        return $etudiant
              ->historiques()
              ->with([
                'etudiant.personne',
                'annee_universitaire',
                'parcour',
                'status'
                ])
            ->get();

    }

    public function getResultExam(Request $request,Historique $historique)
    {

        $final_public=PublicFinal::where('annee_universitaire_id',$historique->annee_universitaire_id)
                                ->where('parcour_id',$historique->parcour_id)
                                ->first();

        $ues_public=UePublic::where('ue_publics.annee_universitaire_id',$historique->annee_universitaire_id)
                ->where('ue_publics.parcour_id',$historique->parcour_id)
                ->get();


        $semestre_public=SemestrePublic::where('parcour_id',$historique->parcour_id)
            ->where('annee_universitaire_id',$historique->annee_universitaire_id)
            ->first();

        $ue_semestre_public_ids=$ues_public->map(function($item){
                    return [
                        $item->semestre_id
                    ];
                });

        $MoyenneService=new MoyenneService;

        

        $resultat=$historique->moyenne_ues()
                ->with([
                    'semestre','ue',
                    'ue.matieres.notes'=>function($note_query) use($historique){
                        $note_query->where('notes.historique_id',$historique->id)
                                    ->where('notes.session_id',2);//session normal
                    },
                    'ue.matieres.moyenne_matieres'=>function($moyenne_query) use($historique){
                        $moyenne_query->where('historique_id',$historique->id);
                    },
                ])
                ->whereHas('ue',function($ue) use ($ue_semestre_public_ids,$historique){
                    $ue->whereHas('ue_publics',function($ue_public) use ($ue_semestre_public_ids,$historique){
                        $ue_public->where('ue_publics.annee_universitaire_id',$historique->annee_universitaire_id)
                                  ->where('ue_publics.parcour_id',$historique->parcour_id)
                                  ->whereIn('ue_publics.semestre_id',$ue_semestre_public_ids);
                    });
                })
                ->get()
                ->map(function($item) use ($ues_public,$MoyenneService,$historique){

                    $ue=$ues_public->where('ue_id',$item->ue->id)->first();

                    return [
                        'ue'       =>$item->ue->ue,
                        'option'   =>$item->ue->option,
                        'matieres' =>$item->ue->matieres->map(function($matiere) use($ue,$MoyenneService,$historique){

                            //pour le resultat final (apres rattrapage)

                            $moyenne_final=$matiere->moyenne_matieres->first()->valeur;

                            //pour le session normal
                            $note_normal=$matiere->notes->first()->valeur;
                            $note_matiere=$matiere->tp?$MoyenneService->getMoyenneTp($matiere,$historique,$note_normal):$note_normal;

                            return [
                                'matiere' =>$matiere->matiere,
                                'validation' =>$ue->avec_ratrapage?($moyenne_final>=10?'V':'NV'):($note_matiere>=10?'V':'NV')
                            ];
                        }),
                        'semestre' =>$item->semestre->semestre,
                        'valeur_session_normal'    =>$item->valeur_session_normal>=10?'V':'NV',
                        'validation'=>$ue->avec_ratrapage?($item->valeur>=10?'V':'NV'):false
                    ];
                });


        
        return [
            "nom"               => $historique->etudiant->personne->nom,
            "prenom"            => $historique->etudiant->personne->prenom,
            "annee"             => $historique->annee_universitaire->valeur,
            "final"             => $final_public?$this->finalResult($historique):false,
            "resultat"          => $resultat,
            "resultat_semestres" => $semestre_public?$this->semestre_result($historique):false
        ];

    }



    private function finalResult(Historique $historique)
    {

        return $historique->moyenne_annee->is_admis?"ADMIS":"RENVOYER";

    }

    private function semestre_result(Historique $historique)
    {

        $semestre_public=SemestrePublic::where('parcour_id',$historique->parcour_id)
        ->where('annee_universitaire_id',$historique->annee_universitaire_id)
        ->get();

        $semestre_public_ids=$semestre_public->map(function($item){
                return [
                    $item->semestre_id
                ];
            });

        $result=$historique->moyenne_semestres()
            ->with("semestre")
            ->whereIn('semestre_id',$semestre_public_ids)
            ->get()
            ->map(function($item){
                return [
                    'semestre' =>$item->semestre->semestre,
                    "validation"=>$item->validation
                ];
            });

        return $result;
    }


}
