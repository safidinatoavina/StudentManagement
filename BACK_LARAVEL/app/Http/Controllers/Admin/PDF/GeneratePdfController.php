<?php

namespace App\Http\Controllers\Admin\PDF;

use App\Models\Historique;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Filter\FilterService;
use App\Service\Impression\ImpressionEtudiant;

class GeneratePdfController extends Controller
{
    public function generateListeNoteOrEtudiant(Request $request){

        $pdf=new ImpressionEtudiant;

        $filter=new FilterService();

		$filter->setQuery($request);

		$data=$filter->handle();

        return $pdf->GeneratePDF($data,'PDF.etudiant');

    }

    public function generateListeForProfesseur(Request $request){

        $request->validate([
            'parcour_id'=>'required',
            'matiere_id'=>'required',
            'session_id'=>'required',
            'semestre_id'=>'required',
            'annee_universitaire_id'=>'required'
        ]);

        $pdf=new ImpressionEtudiant;
        $data=[];
        $data['header']=$request->header??[];
        $data['en_cours']= enCours();


        $data['list']=Historique::with(['etudiant','etudiant.personne',
                'notes'=>function($query) use ($request){
                    $query->where('session_id',$request->session_id)
                        ->where('semestre_id',$request->semestre_id)
                        ->where('matiere_id',$request->matiere_id);
                }])
                ->where('annee_universitaire_id',$request->annee_universitaire_id)
                ->where('parcour_id',$request->parcour_id)
                ->get()->sortBy([
                    ['etudiant.personne.nom','asc'],
                    ['etudiant.personne.prenom','asc'],
                ]);


        return $pdf->GeneratePDF($data,'PDF.prof-etudiant');

    }
}
