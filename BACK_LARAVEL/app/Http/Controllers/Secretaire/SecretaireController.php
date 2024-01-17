<?php

namespace App\Http\Controllers\Secretaire;

use PDF;
use App\Models\Parcour;
use App\Models\Historique;
use Illuminate\Http\Request;
use App\Models\AnneeUniversitaire;
use App\Http\Controllers\Controller;

class SecretaireController extends Controller
{
    use SecretaireTrait;

    public function EtudiantFilter(Request $request){
        $request->validate([
            'annee_universitaire_id'=> 'required',
        ]);

        $result=Historique::with(['annee_universitaire','parcour','status','etudiant','etudiant.personne'])
                    ->where('annee_universitaire_id',$request->annee_universitaire_id); 
                    
        if($request->numeroInscription){
            $result=$result->where('numeroInscription',$request->numeroInscription);
        }

        if($request->nom){
            $result=$result->whereHas('etudiant',function($query) use ($request){
                $query->whereHas('personne',function($query1) use ($request){
                    $query1->where('personnes.nom',$request->nom);
                });
            });
        }

        if($request->prenom){
            $result=$result->whereHas('etudiant',function($query) use ($request){
                $query->whereHas('personne',function($query1) use ($request){
                    $query1->where('personnes.prenom',$request->prenom);
                });
            });
        }


        if($result->exists()){
            return $result->get();
        }else{
            abort(400,"etudiant introuvable");
        }

    }

    public function ReleveNoteEtudiant(Historique $historique){

        $data=$this->getListNote($historique);



        $pdf = PDF::loadView('exports.pdf-releve-note', [
            'data' => $data
        ]);


        $name="releve-note.pdf";

        return $pdf->download($name);

    }

    public function FichePresence(Parcour $parcour){

        $historiques=$parcour->historiques()
                ->with(['etudiant','etudiant.personne'])
                ->where('annee_universitaire_id',annee()->id)
                ->get();

        $pdf = PDF::loadView('exports.pdf-fiche-presence', [
            'historiques' => $historiques,
            'parcour'     => $parcour->parcour,
            'annee'       =>annee()->valeur,
            'semestre'    => enCours()->semestre->semestre,
            'session'     => enCours()->session->session
        ]);

        return $pdf->download("ficke.pdf");


    }

}
