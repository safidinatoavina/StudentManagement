<?php

namespace App\Http\Controllers\Etudiant;

use App\Models\Ue;
use App\Models\Note;
use App\Models\NoteTp;
use App\Models\Parcour;
use App\Models\Session;
use App\Models\Etudiant;
use App\Models\Personne;
use App\Models\Semestre;
use App\Models\MoyenneUe;
use App\Models\Historique;
use App\Models\MoyenneAnnee;
use Illuminate\Http\Request;
use App\Models\CountEtudiant;
use App\Models\MoyenneMatiere;
use App\Models\MoyenneSemestre;
use App\Service\ResponseService;
use App\Models\AnneeUniversitaire;
use App\Service\Cache\SystemeCache;
use App\Http\Controllers\Controller;

class EtudiantController extends Controller
{
    use EtudiantTrait;


    
    /**
     *  liste de tous les etudiants
     */

    public function etudiants()
    {
        $cache=new SystemeCache;

        return $cache->liste_etudiant_inscrit();

    }


    public function liste_etudiant_inscrit(){
        return Etudiant::with(['personne','historiques'=>function($historique){
                    $historique->with('parcour','annee_universitaire','status')->orderBy('annee_universitaire_id','asc'); 
                }])
                ->whereHas('historiques',function($query){
                    $query->where('historiques.annee_universitaire_id',annee()->id);
                })
                ->get()->toArray();
    }

    /**
     *  creer etudinat
     */

    public function store(Request $request)
    {
        $request->validate([
            'nom'               =>   'required',
            'prenom'            =>   'nullable',
            'date_naissance'    =>   'required',
            'lieu_naissance'    =>   'required',
            'cin'               =>   'nullable',
            'address'           =>   'required',
            'parcour_id'        =>   'required',
            'numeroInscription' =>   'required|unique:historiques'
        ]);


        $data=$request->all();

        $personne=Personne::create([
            "nom"            =>$request->nom,
            "prenom"         => $request->prenom,
            "date_naissance" => $request->date_naissance,
            "lieu_naissance" => $request->lieu_naissance,
            "cin"            => $request->cin,
            "address"        => $request->address
        ]);

        $etudiant=Etudiant::create([
            'personne_id'=> $personne->id
        ]);

        $historique=$this->creationHistorique($request,$etudiant->id);


        $count_etudiants=annee()->count_etudiants;

        if($count_etudiants){
            $count_etudiants->update(['count'=>$count_etudiants->count+1]);
        }else{
            CountEtudiant::create([
                'count'=>1,
                'annee_universitaire_id'=>annee()->id
            ]);
        }


        return Etudiant::with(['personne','historiques'=>function($historique){
            $historique->with('parcour','annee_universitaire','status'); 
        }])->where('id',$etudiant->id)->first();
    }

    /**
     *  obtenir l'information de l'etudiant donnee
     */

    public function etudiant(Etudiant $etudiant)
    {
        $result= $etudiant->personne->only(['nom','prenom','age','cin']);
        $result['etudiant_id']=$etudiant->id;

        return $result;
    }

    public function etudiantParcour(Parcour $parcour){

        if(!$parcour)
            abort(403,"Auccun parcour assigné");
        
        $anne=annee();

        $data = Historique::with(['parcour','etudiant','etudiant.personne','status','annee_universitaire'])
            ->whereHas('annee_universitaire',function($query) use($anne){
                $query->where('annee_universitaires.id',$anne->id);
            })
            ->where('parcour_id',$parcour->id)
            ->get()
            ->sortBy([
                ['etudiant.personne.nom','asc'],
                ['etudiant.personne.prenom','asc']
            ]);

        $result=[];

        foreach ($data as $key => $value) {
            $result[]=$value;
        }

        return $result;

    }

    /**
     *  update etudiant
     */

    public function update(Request $request,Etudiant $etudiant)
    {
        $request->validate([
            'nom'               =>   'required',
            'prenom'            =>   'required',
            'date_naissance'    =>   'required',
            'lieu_naissance'    =>   'required',
            'cin'               =>   'required',
            'address'           =>   'required',
        ]);

        $data=$request->all();

        $etudiant->personne->update([
            "nom"            =>$request->nom,
            "prenom"         => $request->prenom,
            "date_naissance" => $request->date_naissance,
            "lieu_naissance" => $request->lieu_naissance,
            "cin"            => $request->cin,
            "address"        => $request->address
        ]);

        return Etudiant::with(['personne','historiques'=>function($historique){
            $historique->with('parcour','annee_universitaire','status'); 
        }])->where('id',$etudiant->id)->first();

    }

    /**
     *  suppression
     *  une fois etudiant est supprimée, etudiant aussi
     */

    public function delete(Personne $personne)
    {

        $historiques=$personne->etudiant->historiques;

        $historiques->each(function($historique){

            $count_etudiants=$historique->annee_universitaire->count_etudiants;

            if($count_etudiants){
                $count_etudiants->update(['count'=>$count_etudiants->count-1]);
            }

        });

        $personne->delete();


        return ResponseService::delete(true);
    }
    

    public function deleteHistorique(Historique $historique)
    {
        $etudiant_id=$historique->etudiant->id;
        $historique->delete();

        return Etudiant::with(['personne','historiques'=>function($historique){
            $historique->with('parcour','annee_universitaire','status'); 
        }])->where('id',$etudiant_id)->first();
    }

    /**
     *  tous les personne
     */

    public function etudiantHistoriques(Etudiant $etudiant)
    {
        return $etudiant->historiques                    
                    ->with(['etudiant','etudiant.personne'])
                    ->where('etudiant_id',$etudiant->id)
                    ->get();
    }

    /**
     *  liste des etudiant dans un année univesitaire donnée
     */

    public function etudinatsAnnee(AnneeUniversitaire $annee)
    {
        return $annee->historiques
                    ->with(['etudiant','etudiant.personne'])
                    ->where('annee_universitaire_id',$annee->id)
                    ->get();
    }


    public function creationHistorique($request,$etudiant_id)
    {

        //verifier si cette étudiant est accès à cette inscription
        validerHistorique($etudiant_id,$request->parcour_id,annee()->id);

        $historique=Historique::create([
            'etudiant_id'            =>$etudiant_id,
            'parcour_id'             => $request->parcour_id,
            'annee_universitaire_id' => annee()->id,
            'status_id'              => $request->status_id??1,//passant
            'numeroInscription'      => $request->numeroInscription
        ]);

        $this->createDefaultNote(Parcour::findOrFail($request->parcour_id),$historique);
        
        return $historique;
    }


    public function creationHistoriqueDetail(Request $request,$etudiant_id)
    {

        validerHistorique($etudiant_id,$request->parcour_id,$request->annee_universitaire_id??annee()->id);

        $historique=Historique::create([
            'etudiant_id'            =>$etudiant_id,
            'parcour_id'             => $request->parcour_id,
            'annee_universitaire_id' => $request->annee_universitaire_id??annee()->id,
            'status_id'              => $request->status_id??1,//passant
            'numeroInscription'      => $request->numeroInscription
        ]);

        $this->createDefaultNote(Parcour::findOrFail($request->parcour_id),$historique);
        
        return Etudiant::with(['personne','historiques'=>function($historique){
                    $historique->with('parcour','annee_universitaire','status'); 
                }])->where('id',$etudiant_id)->first();   
                 
    }
    
    //--------------------------private function----------------------------

    private function createDefaultNote(Parcour $parcour,Historique $historique){

        $matieres_actif=$parcour->matieres()->with('tp')->whereStatus(1)->get();

        foreach (Session::all() as $session) {

            foreach ($matieres_actif as $matiere) {
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

        foreach ($matieres_actif as $matiere) {

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
                $query->where('parcour_id',$parcour->id)->where('semestre_id',$semestre->id)->whereStatus(1);
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
