<?php

namespace App\Http\Controllers\reinscription;

use App\Models\Ue;
use App\Models\Note;
use App\Models\NoteTp;
use App\Models\Parcour;
use App\Models\Session;
use App\Models\Etudiant;
use App\Models\Semestre;
use App\Models\MoyenneUe;
use App\Models\Historique;
use App\Models\MoyenneAnnee;
use Illuminate\Http\Request;
use App\Models\MoyenneMatiere;
use App\Models\MoyenneSemestre;
use App\Models\AnneeUniversitaire;
use App\Http\Controllers\Controller;
use App\Service\Moyenne\MoyenneService;

class ReinscriptionController extends Controller
{


    public function ListeAdmis(Parcour $parcour)
    {

         $filter=$parcour->historiques()
            ->with([
                'etudiant',
                'etudiant.personne',
            ])
            ->where('annee_universitaire_id',annee()->id)
            ->whereHas('moyenne_annee',function($query){
                $query->where('moyenne_annees.is_admis',1);
            })
            ->get();

        $result=[];

        $filter->each(function($item) use (&$result){
            $result[]=[
                "historique_id"   => $item->id,
                "numeroInscription"=>$item->numeroInscription,
                "nom"             =>$item->etudiant->personne->nom,
                "prenom"             =>$item->etudiant->personne->prenom
            ];
        });


        return [
            'liste'=>$result
        ];

    }
    




    public function ListeRedoublants(Parcour $parcour){

        
        $filter=$parcour->historiques()
            ->with([
                'etudiant',
                'etudiant.personne',
                'moyenne_ues'=>function($query){
                    $query->where('valeur','>=',10);
                },
                'moyenne_annee'
            ])
            ->where('annee_universitaire_id',annee()->id)
            ->whereHas('moyenne_annee',function($query){
                $query->where('moyenne_annees.is_admis',0);
            })
            ->get();

        $result=[];

        $filter->each(function($item) use (&$result){

            $result[]=[
                "historique_id"    => $item->id,
                "numeroInscription"=> $item->numeroInscription,
                "nom"              => $item->etudiant->personne->nom,
                "prenom"           => $item->etudiant->personne->prenom
            ];
            
        });


        return [
            'liste'=>$result
        ];;
    }

    
    public function ReinscriptionRedoublante(Request $request,Etudiant $etudiant)
    {

        $request->validate([
            'numeroInscription'=> 'required|unique:historiques',
            'parcour_id'       => 'required'
        ]);

        $annee_id=$this->NewYear();

        $parcour=Parcour::findOrFail($request->parcour_id);


        $added_in_new_years=AnneeUniversitaire::findOrFail($annee_id)
                ->historiques()
                ->where('etudiant_id',$etudiant->id)
                ->get()->map(function($item){
                    return $item->parcour_id;
                })->toArray();

        if(in_array($parcour->id,$added_in_new_years))
            abort(400,"Etudiant déja inscrit dans cette parcour");
    

        $new_historique=Historique::create([
            'etudiant_id'            =>$etudiant->id,
            'parcour_id'             => $parcour->id,
            'annee_universitaire_id' => $annee_id,
            'status_id'              => 2,
            'numeroInscription'      => $request->numeroInscription
        ]);

        $new_historique=Historique::find($new_historique->id);

        $last_parcour_historique=$etudiant->historiques()->where('parcour_id',$parcour->id)->where('id','!=',$new_historique->id)->first();

        if(!$last_parcour_historique){
            abort(400,"cette etudiant n'est pas rédoublant");
        }

        if(!$this->hasWhiteYear($last_parcour_historique->annee_universitaire_id,$new_historique->annee_universitaire_id)){
            $this->createDefaultNote($parcour,$new_historique); //le note valide est gardé
        }
        else{
            $this->createDefaultNotePassant($parcour,$new_historique); //le note validé n'est pas gardé
        }
        
        return "succès";
    }

    public function ReinscriptionNormal(Request $request,Etudiant $etudiant,$statut=1)
    {


        $request->validate([
            'numeroInscription'=> 'required|unique:historiques',
            'parcour_id'       => 'required'
        ]);

        $annee_id=$this->NewYear();
        
        $parcour=Parcour::findOrFail($request->parcour_id);

        $added_in_new_years=AnneeUniversitaire::findOrFail($annee_id)
                    ->historiques()
                    ->where('etudiant_id',$etudiant->id)
                    ->get()->map(function($item){
                        return $item->parcour_id;
                    })->toArray();

        if(in_array($parcour->id,$added_in_new_years))
            abort(400,"Etudiant déja inscrit dans cette parcour");


        $historique=Historique::create([
            'etudiant_id'            =>$etudiant->id,
            'parcour_id'             => $parcour->id,
            'annee_universitaire_id' => $annee_id,
            'status_id'              => $statut,
            'numeroInscription'      => $request->numeroInscription
        ]);


        $this->createDefaultNotePassant($parcour,$historique);


    }


    public function getResults(Request $request){

        if(!$request->nom && !$request->prenom && !$request->parcour_id){
            abort(422,"Veuillez remplir au moins une des formulaires");
        }

        $query = Etudiant::with(['personne','historiques','historiques.annee_universitaire','historiques.parcour','historiques.status']);

        if($request->nom){
            $query = $query->whereHas('personne',function($q) use ($request){
                $q->where('nom',$request->nom);
            });
        }

        if($request->prenom){
            $query = $query->whereHas('personne',function($q) use ($request){
                $q->where('prenom',$request->prenom);
            });
        }

        if($request->parcour_id){
            $query = $query->whereHas('historiques',function($q) use ($request){
                $q->whereHas('parcour',function($q1) use ($request){
                    $q1->where('parcours.id',$request->parcour_id);
                });
            });
        }


        return $query->limit(20)->get()->map(function ($item){



            return [
                'etudiant_id'       =>$item->id,
                'nom'               =>$item->personne->nom,
                'prenom'            =>$item->personne->prenom,
                'nom_complet'         => $item->personne->nom.' '.$item->personne->prenom,
                'historiques'       =>$item->historiques->map(function($historique) use ($item){
                    return [
                        'historique_id'       => $historique->id,
                        'numeroInscription'   => $historique->numeroInscription,
                        'annee_universitaire' => $historique->annee_universitaire->valeur,
                        'parcour'             => $historique->parcour->abreviation,
                        'statut'              => $historique->status->abreviation
                    ];
                })

            ];

        });


    }

    public function handleReinscription(Request $request){

        $etudiant=Etudiant::findOrFail($request->etudiant_id);
        $to_parcour=Parcour::findOrFail($request->to_parcour_id);

        $is_redoublant =  $etudiant->historiques()->where('parcour_id',$to_parcour->id)->exists();


        $new_request = new Request;
        $new_request->merge([
            'numeroInscription'=> $request->numeroInscription,
            'parcour_id'       => $to_parcour->id
        ]);
        

        if($is_redoublant){
            $this->ReinscriptionRedoublante($new_request,$etudiant);
        }else{ //passant ou reinscription dans une autre 
            $this->ReinscriptionNormal($new_request,$etudiant);
        }

        return true;

    }

    private function hasWhiteYear($last_id,$new_id){
        $last=AnneeUniversitaire::findOrFail($last_id);
        $new=AnneeUniversitaire::findOrFail($new_id);
        $lastTab=\explode('-',$last->valeur);
        $newTab=\explode('-',$new->valeur);
        $diff=$newTab[0]-$lastTab[0];
        if($diff>1)
            true;
        return false;
    }


    private function NewYear(){
        
        $lasts=AnneeUniversitaire::orderBy('id','desc')->first();
        
        //si le dernier annee est l'anne en cours
        if($lasts->id==annee()->id){

            $table=explode('-',annee()->valeur);
            $annee_dernier=end($table);
            $dernier_plus=$annee_dernier+1;
            $valeur="$annee_dernier-$dernier_plus";

            $new=AnneeUniversitaire::create([
                'valeur'=>$valeur,
                'statut'=>0
            ]);

            if($new)
                return $new->id;
            else
                abort(400,"erreur création nouvelle année");

        }else{

           $lists = AnneeUniversitaire::orderBy('id','desc')->get();
           $is_ok=false;

           //juste une initilisation
           $prev=new AnneeUniversitaire;

           foreach ($lists as $anne_item) {

                if($anne_item->id==annee()->id){
                    $is_ok=true;
                    return $prev->id;
                }

                $prev=$anne_item;
                
           }

           if(!$is_ok)
            abort(400,"auccun année pour la réinscription");


        }

    }



    private function createDefaultNote(Parcour $parcour,Historique $historique){


        //-------------semestre et session des matieres----------------------------------------
        foreach (Session::all() as $session) {

            foreach ($parcour->matieres as $matiere) {

                $moyenne=$historique->moyenne_matieres()->where('matiere_id',$matiere->id)->first();

                if($moyenne){

                    //garder la note si ue valide

                    $moyenne_ue_historique=$historique->moyenne_ues()->whereHas('ue',function($query_ue) use($matiere){
                        $query_ue->whereHas('matieres',function($query_matiere) use($matiere) {
                            $query_matiere->where('matieres.id',$matiere->id);
                        });
                    })->first()->valeur ;

                    if($moyenne_ue_historique->valeur >= 10){

                        $note=$historique->notes()
                            ->where('matiere_id',$matiere->id)
                            ->where('session_id',$session->id)
                            ->first();

                        Note::create([
                            'historique_id'=>$historique->id,
                            'matiere_id'   => $matiere->id,
                            'semestre_id'  => $matiere->semestre_id,
                            'session_id'   => $session->id,
                            'valeur'       =>$note->valeur,
                            'is_set'       =>1
                        ]);

                        if($matiere->tp){
                            NoteTp::create([
                                'valeur' => $historique->note_tps()->where('matiere_id',$matiere->id)->first()->valeur,
                                'tp_id' =>$matiere->tp->id,
                                'historique_id' => $historique->id,
                                'is_set'  =>1
                            ]);
                        }

                        continue;
                    }
                }

                Note::create([
                    'historique_id'=>$historique->id,
                    'matiere_id'   => $matiere->id,
                    'semestre_id'  => $matiere->semestre_id,
                    'session_id'   => $session->id,
                    'valeur'       =>0,
                    'is_set'       =>0
                ]);

                if($matiere->tp){
                    NoteTp::create([
                        'valeur' => 0,
                        'tp_id' =>$matiere->tp->id,
                        'historique_id' => $historique->id,
                        'is_set'  =>0
                    ]);
                }

            }

        }



    //-------------par matieres----------------------------------------

        $parcours_matiers=$parcour->matieres()->with('tp')->get();

        foreach ($parcours_matiers as $matiere) {

            MoyenneMatiere::create([
                'valeur'        =>0,
                'matiere_id'    =>$matiere->id,
                'historique_id' =>$historique->id,
                'default_coefficient'   => $matiere->coefficient
            ]);

        }

        //-------------par ues----------------------------------------


        foreach (Semestre::all() as $semestre) {

            $ues=Ue::whereHas('matieres',function($query) use ($parcour,$semestre){
                $query->where('parcour_id',$parcour->id)->where('semestre_id',$semestre->id);
            })->get();

            foreach($ues as $item){

                MoyenneUe::create([
                    'valeur'                => 0,
                    'ue_id'                 => $item->id,
                    'semestre_id'           => $semestre->id,
                    'historique_id'         => $historique->id,
                    'default_credit'        => $item->credit,
                    'default_coefficient'   => $item->coefficient
                ]);


            }


        }

            //-------------par semestres----------------------------------------



        foreach (Semestre::all() as $semestre) {

            MoyenneSemestre::create([
                'valeur'        =>0,
                'semestre_id'   =>$semestre->id,
                'historique_id' =>$historique->id
            ]);

        }

            //-------------par année----------------------------------------


        MoyenneAnnee::create([
            'valeur'        => 0,
            'historique_id' => $historique->id
        ]);



        //recalculer tous moyenne


        foreach ($parcour->matieres as $matiere) {

            $MoyenneService=new MoyenneService;

            $MoyenneService->updateMoyenne($matiere,$historique);

        }



    }



    private function createDefaultNotePassant(Parcour $parcour,Historique $historique){

        foreach (Session::all() as $session) {

            foreach ($parcour->matieres as $matiere) {
                Note::create([
                    'historique_id'=>$historique->id,
                    'matiere_id'   => $matiere->id,
                    'semestre_id'  => $matiere->semestre_id,
                    'session_id'   => $session->id,
                    'valeur'       =>0,
                    'is_set'       =>0
                ]);

            }

        }

        foreach ($parcour->matieres as $matiere) {

            MoyenneMatiere::create([
                'valeur'        =>0,
                'matiere_id'    =>$matiere->id,
                'historique_id' =>$historique->id,
                'default_coefficient'   => $matiere->coefficient
            ]);

            if($matiere->tp)
                NoteTp::create([
                    'tp_id' =>$matiere->tp->id,
                    'historique_id' => $historique->id
                ]);

        }


        foreach (Semestre::all() as $semestre) {

            $ues=Ue::whereHas('matieres',function($query) use ($parcour,$semestre){
                $query->where('parcour_id',$parcour->id)->where('semestre_id',$semestre->id);
            })->get();

            $ues->each(function ($items) use ($semestre,$historique){

                MoyenneUe::create([
                    'valeur'                => 0,
                    'ue_id'                 => $items->id,
                    'semestre_id'           => $semestre->id,
                    'historique_id'         => $historique->id,
                    'default_credit'        => $items->credit,
                    'default_coefficient'   => $items->coefficient
                ]);

            });

        }


        foreach (Semestre::all() as $semestre) {

            MoyenneSemestre::create([
                'valeur'        =>0,
                'semestre_id'   =>$semestre->id,
                'historique_id' =>$historique->id
            ]);
            
        }


        MoyenneAnnee::create([
            'valeur'        => 0,
            'historique_id' => $historique->id
        ]);


    }



}
