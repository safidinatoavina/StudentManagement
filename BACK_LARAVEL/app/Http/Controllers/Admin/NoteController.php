<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\TP;
use App\Models\Note;
use App\Models\NoteTp;
use App\Models\Matiere;
use App\Models\Parcour;
use App\Models\Session;
use App\Models\Historique;
use Illuminate\Http\Request;
use App\Service\ResponseService;
use App\Models\AnneeUniversitaire;
use App\Http\Controllers\Controller;
use App\Service\Etudiant\Validation;
use App\Service\Moyenne\MoyenneService;

class NoteController extends Controller
{
    use Validation;
    

    public function index()
    {
        return Note::with(['historique','session','matiere'])->orderBy('id','desc')->get();
    }


    public function show(Note $note)
    {
        return Note::with(['historique','session','matiere'])
            ->where('id',$note->id)
            ->first();
    }

    public function store(Request $request)
    {

        $request->validate([
            'id'            => 'required|integer',
            'historique_id' => 'required|integer',
            'matiere_id'    => 'required|integer',
            'valeur'        => 'required|numeric|min:0|max:20'
        ]);


        $data=[
            'historique_id' =>$request->historique_id,
            'session_id'    =>$request->session_id ?? enCours()->session_id,
            'semestre_id'   =>$request->semestre_id ?? enCours()->semestre_id,
            'matiere_id'    => $request->matiere_id,
            'is_set'        =>1,
            'valeur'        => $request->valeur
        ];

        $historique=Historique::find($request->historique_id);

        $MoyenneService=new MoyenneService;
        $matiere=Matiere::find($data['matiere_id']);


        if(!$historique)
        {
            $hist=$request->historique_id;
            abort(400,"La valeur de l'historique ($hist) est indéfinie");
        }

        if(!$this->CanAddNote($historique,$matiere))
            abort(400,"Erreur, la valeur de cette note a déjà été insérée");

        $note=Note::find($request->id);


        $MoyenneService->validateUeOption($matiere,$historique);

        $this->authorize('create', $note);

        if($note){
            $note->update([
                'valeur'=>$request->valeur,
                'is_set'=>1
            ]);
        }
        else{
            abort(400,"cette étudiant n'est pas suivre le regle d'inscription");
        }



        $MoyenneService->updateMoyenne(
            $matiere,
            $historique
        );


        return $this->matiereNotes($matiere);

    }

    public function storeTP(Request $request)
    {

        $request->validate([
            'id'            => 'required|integer',
            'historique_id' => 'required|integer',
            'tp_id'    => 'required|integer',
            'valeur'        => 'required|numeric|min:0|max:20'
        ]);


        $data=[
            'historique_id' =>$request->historique_id,
            'tp_id'    => $request->tp_id,
            'is_set'        =>1,
            'valeur'        => $request->valeur
        ];

        $historique=Historique::find($request->historique_id);
        $MoyenneService=new MoyenneService;


        if(!$historique)
        {
            $hist=$request->historique_id;
            abort(400,"La valeur de l'historique ($hist) est indéfinie");
        }

        if(!$this->CanAddNoteTP($historique,TP::find($data['tp_id'])))
            abort(400,"Erreur, la valeur de cette note a déjà été insérée");

        $note=NoteTp::find($request->id);

        $this->authorize('create', $note);

        $MoyenneService->validateUeOption($note->tp->matiere,$historique);

        if($note){
            $note->update([
                'valeur'=>$request->valeur,
                'is_set'=>1
            ]);
        }
        else{
            abort(400,"cette étudiant n'est pas suivre le regle d'inscription");
        }


        $MoyenneService->updateMoyenne(
            $note->tp->matiere,
            $historique
        );


        return $this->matiereNotesTP($note->tp);

    }

    public function update(Request $request,Note $note)
    {

        $request->validate(['valeur'=>'required|numeric|min:0|max:20']);

        $data=[
            'valeur'        => $request->valeur,
            'is_set'        => 1
        ];

        $MoyenneService=new MoyenneService;

        $MoyenneService->validateUeOption($note->matiere,$note->historique);

        $note->update($data);

        $MoyenneService->updateMoyenne(
            $note->matiere,
            $note->historique
        );


        return ResponseService::updated(true);
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return ResponseService::delete(true);
    }

    //______________plus_____________________

    public function matiereNotes(Matiere $matiere)
    {

        $this->is_autorize($matiere);

        $data = $matiere->notes()
            ->whereHas('historique',function($query){
                $query->where('historiques.annee_universitaire_id',annee()->id);
            })
            ->with(['historique','historique.etudiant','historique.etudiant.personne'])
            ->where('session_id',enCours()->session_id)
            ->where('semestre_id',enCours()->semestre_id)
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


    public function matiereNotesTP(TP $tp)
    {

        $this->is_autorize_tp($tp);

        $data = $tp->note_tps()
            ->whereHas('historique',function($query){
                $query->where('historiques.annee_universitaire_id',annee()->id);
            })
            ->whereHas('tp',function($query_tp){
                $query_tp->whereHas('matiere',function($matiere_query){
                    $matiere_query->where('semestre_id',enCours()->semestre_id);
                });
            })
            ->with(['historique','historique.etudiant','historique.etudiant.personne'])
            ->get();

        $result=[];

        foreach ($data as $key => $value) {
            $result[]=$value;
        }

        return $result;
    }


    public function validationUeMatiere(Matiere $matiere){



        $ues_public= $matiere->ue->ue_publics()
                ->where('ue_publics.annee_universitaire_id',annee()->id)
                ->where('ue_publics.parcour_id',$matiere->parcour_id)
                ->first();

        if(!$ues_public)
            abort(400,"UE n'est pas encore publique");

        $ue_semestre_public_ids=[$ues_public->semestre_id];

        $MoyenneService=new MoyenneService;

        

        return $matiere->ue->moyenne_ues()
                ->with([
                    'semestre','ue',
                    'ue.matieres.notes'=>function($note_query) {
                        $note_query->whereHas('historique',function($query_historique){
                            $query_historique->where('historiques.annee_universitaire_id',annee()->id);
                        })
                        ->where('notes.session_id',2);//session normal
                    },
                    'ue.matieres.moyenne_matieres'=>function($moyenne_query){
                        $moyenne_query->whereHas('historique',function($query_historique){
                            $query_historique->where('historiques.annee_universitaire_id',annee()->id);
                        });
                    },
                ])
                ->whereHas('ue',function($ue) use ($ue_semestre_public_ids,$matiere){
                    $ue->whereHas('ue_publics',function($ue_public) use ($ue_semestre_public_ids,$matiere){
                        $ue_public->where('ue_publics.annee_universitaire_id',annee()->id)
                                  ->where('ue_publics.parcour_id',$matiere->parcour_id)
                                  ->whereIn('ue_publics.semestre_id',$ue_semestre_public_ids);
                    });
                })
                ->get()
                ->map(function($item) use ($ues_public,$MoyenneService){


                    return [
                        'numeroInscription'      =>$item->historique->numeroInscription,
                        'nom'                    =>$item->historique->etudiant->personne->nom,
                        'prenom'                 =>$item->historique->etudiant->personne->prenom,
                        'ue'                     =>$item->ue->ue,
                        'option'                 =>$item->ue->option,
                        'matieres'               =>$item->ue->matieres->map(function($matiere) use($item,$ues_public,$MoyenneService){

                            //pour le resultat final (apres rattrapage)

                            $moyenne_final=$matiere->moyenne_matieres->where('historique_id',$item->historique->id)->first()->valeur;

                            //pour le session normal
                            $note_normal=$matiere->notes->where('historique_id',$item->historique->id)->first()->valeur;
                            $note_matiere=$matiere->tp?$MoyenneService->getMoyenneTp($matiere,$item->historique,$note_normal):$note_normal;

                            return [
                                'matiere'    =>$matiere->matiere,
                                'validation' =>$ues_public->avec_ratrapage?($moyenne_final>=10?'V':'NV'):($note_matiere>=10?'V':'NV')
                            ];
                        }),
                        'semestre'                 =>$item->semestre->semestre,
                        'valeur_session_normal'    =>$item->valeur_session_normal>=10?'V':'NV',
                        'validation'               =>$ues_public->avec_ratrapage?($item->valeur>=10?'V':'NV'):false
                    ];
                });

        
        

    }


    public function historiqueNotes(Historique $historique)
    {
        return $historique->notes;
    }


    public function sessionNotes(Session $session)
    {
        return $session->notes;
    }

    public function ViderNote(Request $request){

        $request->validate([
            'note_ids'=> 'required'
        ]);

        $MoyenneService=new MoyenneService;

        foreach ($request->note_ids as $note_id) {
            $note=Note::with(['matiere','historique'])->find($note_id);
            $note->update(['is_set'=>0,'valeur'=>0]);
            $MoyenneService->updateMoyenne($note->matiere,$note->historique);
        }

        return $this->matiereNotes($note->matiere);

    }

    public function ViderNoteTp(Request $request){

        $request->validate([
            'note_tp_ids'=> 'required'
        ]);

        $MoyenneService=new MoyenneService;

        foreach ($request->note_tp_ids as $note_id) {
            $note_tp=NoteTp::with(['tp.matiere','historique'])->find($note_id);
            $note_tp->update(['is_set'=>0,'valeur'=>0]);
            $MoyenneService->updateMoyenne($note_tp->tp->matiere,$note_tp->historique);
        }

        return $this->matiereNotesTP($note_tp->tp);

    }


    //--------------------------


    public function is_autorize(Matiere $matiere)
    {
        if (!(
            auth()->user()->roles()->where('roles.id',7)->exists() 
            ||
            auth()->user()->roles()->where('roles.id',1)->exists() 
            ||
            auth()->user()->roles()->where('roles.id',2)->exists()
            ||
            auth()->user()->matieres()->where('matieres.id',$matiere->id)->exists()
        ))
            abort(403,"action n'est pas autorisé");
    }

    public function is_autorize_tp(TP $tp)
    {
        if (!(
            auth()->user()->roles()->where('roles.id',7)->exists() 
            ||
            auth()->user()->roles()->where('roles.id',1)->exists() 
            ||
            auth()->user()->roles()->where('roles.id',2)->exists()
            ||
            auth()->user()->tps()->where('t_p_s.id',$tp->id)->exists()
        ))
            abort(403,"action n'est pas autorisé");
    }

}
