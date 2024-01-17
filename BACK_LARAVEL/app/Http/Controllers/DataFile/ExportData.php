<?php

namespace App\Http\Controllers\DataFile;

use Excel;
use App\Models\TP;
use App\Models\Matiere;
use App\Models\Parcour;
use App\Models\Semestre;
use App\Models\Historique;
use App\Jobs\BaseExportJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\exportUEHead;
use App\Exports\HeadProfExport;
use App\Exports\exportMoyenneTp;
use App\Exports\MoyenneUeExport;
use App\Exports\exportMatiereHead;
use App\Exports\HeadEtudiantExport;
use App\Exports\MoyenneMatiereExport;
use App\Exports\PassageANiveauExport;
use App\Exports\exportTemplateAddNote;
use App\Exports\MoyenneSemestreExport;
use App\Exports\exportValidationUeEcue;
use App\Exports\exportTemplateAddNoteTP;
use App\Service\Operation\OperationService;
use App\Http\Controllers\Admin\NoteController;
use App\Service\Notification\NotificationService;
use App\Http\Controllers\Admin\Operation\OperationController;

trait ExportData {

    public function getNoteTp(Request $request,$type){
        
        $name="moyenne-tp-".$request->parcour.'.'.$type;

        $tp=TP::findOrFail($request->tp_id);

        $type_result=$request->is_validation;
        $data=$tp->note_tps()
            ->with(['tp','historique','historique.etudiant.personne'])
            ->whereHas('historique',function($historique_query){
                $historique_query->where('annee_universitaire_id',annee()->id);
            })
            ->get()
            ->sortBy([
                    ['historique.etudiant.personne.nom','asc'],
                    ['historique.etudiant.personne.prenom','asc'],
                ]);

        if($type!='pdf'){
            return Excel::download(new exportMoyenneTp($data,$type_result,$request->parcour), $name);
        }
        else{
            $pdf = \PDF::loadView('exports.pdf-moyenne-tp', [
            'data' => $data,
            'is_validation' => $type_result,
            'parcour' => $request->parcour
            ]);

           return $pdf->download($name);

        }

    }

    public function exportMoyenneUe(Request $request){
        
        $parcour=Parcour::findOrFail($request->parcour_id);

        $items=collect([]);

        if($parcour){
            $items=$parcour->historiques()
                ->with([
                    'moyenne_ues',
                    'moyenne_ues.ue',
                    'etudiant',
                    'etudiant.personne'
                ])
                ->get()->sortBy([
                    ['etudiant.personne.nom','asc'],
                    ['etudiant.personne.prenom','asc'],
                ]);
        }

        $header=$items->first()->moyenne_ues;


        $data['header']=$header;
        $data['items']=$items;
        $data['form']=$request->form;
        $data['annee']=annee()->valeur;
        $data['parcour']=$parcour;
        /*        $controller= new OperationController;
        $data=$controller->ValidationUe($request);*/

        $type=strtolower($request->type);


        $name="moyenne_ue.$type";

        if($type!='pdf'){
            return Excel::download(new MoyenneMatiereExport($data), $name);
        }
        else{
            $pdf = \PDF::loadView('exports.pdf-moyenne-matiere', [
            'data' => $data
            ]);

           return $pdf->download($name);

        }


            

    }



    public function exportResultatBase(Request $request){

        $NotificationService=new NotificationService;
        
        BaseExportJob::dispatch($request->semestre_id,$request->parcour_id,$request->is_validation,$NotificationService);


        // return view('exports.moyenne-base', [
        //     'data' => $data
        // ]);



        // if($request->save)
        //     Excel::store(new MoyenneUeExport($data),$name);
        // else
        //     Excel::download(new MoyenneUeExport($data),$name);


    }


    public function exportMoyenneSemestre(Request $request){

        $parcour=Parcour::findOrFail($request->parcour_id);

        if(!$request->semestre_id){
            $semestre=enCours()->semestre;
        }else{
            $semestre=Semestre::find($request->semestre_id);
        }

        $operation=new OperationService;    

        $data['items']= $operation->getListMoyenneParSemestre($parcour,$semestre);

        if(count($data['items'])){
            $data['semestre']=$data['items'][0]['semestre'];
            $data['annee']=annee()->valeur;
            $data['form']=$request->form;
        }

        
        $type=strtolower($request->type);
        $name='moyenne_semestre';
        $name="$name.$type";

        if($type=='pdf'){

            $pdf = \PDF::loadView('exports.pdf-moyenne-semestre', [
                'data' => $data
                ]);
    
            return $pdf->download($name);

        }else{
            return Excel::download(new MoyenneSemestreExport($data), "$name");
        }


    }

    public function exportValidationUeEcue(Matiere $matiere){
        $NoteController=new NoteController;
        $data=$NoteController->validationUeMatiere($matiere);
        $header=[
            'parcour'=>$matiere->parcour->parcour,
            'annee'  =>annee()->valeur
        ];

        return Excel::download(new exportValidationUeEcue($data,$header),"validation.xlsx");

    }

    public function getProfHead($type){

        $name='En_tete_prof';
        $name="$name.$type";

        $data=[
            'Nom'               ,
            'Prenom'            ,
            'Date de naissance' ,
            'Lieu de naissance' ,
            'Address'           ,
            'CIN'               ,
            'role'              ,
            'Login'             ,
            'Mot de passe'      ,
        ];

       return Excel::download(new HeadProfExport($data), "$name");


    }

    public function getUEHead($type){

        $name='En_tete_ue';
        $name="$name.$type";

        $data=[
            'UE',
            'Coefficient',
            'Credit',
            'abreviation Parcour'
        ];

        return Excel::download(new exportUEHead($data), "$name");

    }

    public function getMatiereHead($type="xlsx")
    {
        $name='En_tete_matiere';
        $name="$name.$type";

        $data=[
            'ECUE',
            'Abreviation Parcour',
            'Professeur',
            'ue',
            'semestre',
            'coefficient',
        ];

        return Excel::download(new exportMatiereHead($data), "$name");

    }


    public function getEtudiantHead($type){

        $name='En_tete_etudiant';
        $name="$name.$type";

        $data=[
            'Nom',
            'Prenom',
            'Date de naissance',
            'Lieu naissance',
            'Address'       ,
            'N°CIN'          ,
            'N°Inscription' ,
            'Abreviation Parcour'  ,
            'Statut(P,R)'
        ];

        return Excel::download(new HeadEtudiantExport($data), "$name");

    }

    public function exportPassage(Request $request){
        
        
        $controller=new OperationController;

        $data['annee']=annee()->valeur;

        if($request->type=='passant'){

            $name='etudiant_passant';
            $extension=$request->extension;
            $name="$name.$extension";

            if($request->logique){ //tester si c'est une telechargemet du tab critere
                $data['items']=$controller->ListeAdmis($request);
            }else{
                $parcour=Parcour::findOrFail($request->parcour_id);
                $data['items']=$controller->FetchAdmis($parcour);
            }

        }else{

            $name='etudiant_redoublant';
            $extension=$request->extension;
            $name="$name.$extension";
            $path="public/$name";

            if($request->logique){ //tester si c'est une telechargemet du tab critere
                $data['items']=$controller->ListeRedoublants($request);
            }else{
                $parcour=Parcour::findOrFail($request->parcour_id);
                $data['items']=$controller->FetchRedoublant($parcour);
            }



        }


        if($extension=='pdf'){

            $pdf = \PDF::loadView("exports.pdf-passage-niveau", [
                'data' => $data
                ]);
    
            return $pdf->download($name);

        }else{

            return Excel::download(new PassageANiveauExport($data), $name);

        }


    }


    public function exportTemplateNote(Request $request,$type)
    {

        $data=Historique::with(['etudiant','etudiant.personne','parcour'])
        ->where('annee_universitaire_id',annee()->id)
        ->whereHas('parcour',function($parcour) use($request){
            $parcour->where('parcours.id',$request->parcour_id);
        })->whereHas('notes',function($note_query) use ($request){
            $note_query->where('notes.matiere_id',$request->matiere_id)
                    ->where('notes.semestre_id',enCours()->semestre->id)
                    ->where('notes.session_id',enCours()->session->id)
                    ->where('notes.is_set',0);
        })
        ->get()
        ->sortBy([
            ['etudiant.personne.nom','asc'],
            ['etudiant.personne.prenom','asc'],
        ]);

        $head=[
            "parcour"=>Parcour::find($request->parcour_id)->parcour,
            "matiere"=>\App\Models\Matiere::find($request->matiere_id)->matiere
        ];

        return Excel::download(new exportTemplateAddNote($data,$head), "template.$type");
    }


    public function exportTemplateNoteTP(Request $request,$type)
    {

        $data=Historique::with(['etudiant','etudiant.personne','parcour'])
        ->where('annee_universitaire_id',annee()->id)
        ->whereHas('parcour',function($parcour) use($request){
            $parcour->where('parcours.id',$request->parcour_id);
        })->whereHas('note_tps',function($note_query) use ($request){
            $note_query->whereHas('tp',function($tp_query) use ($request){
                $tp_query->where('t_p_s.id',$request->tp_id);
            })->where('note_tps.is_set',0);
        })
        ->get()
        ->sortBy([
            ['etudiant.personne.nom','asc'],
            ['etudiant.personne.prenom','asc'],
        ]);


        $head=[
            "parcour"=>Parcour::find($request->parcour_id)->parcour,
            "tp"=>\App\Models\TP::find($request->tp_id)->tp
        ];

        return Excel::download(new exportTemplateAddNoteTP($data,$head), "template.$type");
    }

}
