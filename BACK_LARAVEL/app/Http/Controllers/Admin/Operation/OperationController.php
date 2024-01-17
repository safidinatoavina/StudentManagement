<?php

namespace App\Http\Controllers\Admin\Operation;

use App\Models\Ue;
use App\Models\Parcour;
use App\Models\Semestre;
use App\Models\UePublic;
use App\Models\PublicFinal;
use App\Models\CritereAdmis;
use Illuminate\Http\Request;
use App\Models\SemestrePublic;
use App\Models\CritereValidation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Service\Operation\OperationService;
use App\Service\Impression\ImpressionEtudiant;



class OperationController extends Controller
{

    use OperationTrait;
    
    private $operation;

    public function __construct(){

        $this->operation=new OperationService;
    }

    public function ValidationUe(Request $request){

        $parcour=Parcour::findOrFail($request->parcour_id);

        if(!$request->semestre_id){
            $semestre=enCours()->semestre;
        }else{
            $semestre=Semestre::find($request->semestre_id);
        }

        return $this->operation->getListMoyenneUeParSemestre($parcour,$semestre);
    }

    public function ValidationMatiere(Request $request){

        $parcour=Parcour::findOrFail($request->id);

        if(!$request->semestre_id){
            $semestre=enCours()->semestre;
        }else{
            $semestre=Semestre::find($request->semestre_id);
        }

        return $this->operation->getListMoyenneParMatiere($parcour,$semestre);
    }

    public function uesJury(Parcour $parcour){


        $parcour_id=$parcour->id;

        return Ue::with(['ue_publics'=>function($query) use ($parcour_id){
                    $query->where('ue_publics.parcour_id',$parcour_id)
                        ->where('annee_universitaire_id',annee()->id)
                        ->where('semestre_id',enCours()->semestre->id);
                }])->whereHas('matieres',function($matiere) use ($parcour_id){
                    $matiere->where('parcour_id',$parcour_id)
                            ->where('semestre_id',enCours()->semestre->id);

                })->get();

    }

    public function ValidationParSemestre(Request $request){

        $parcour=Parcour::findOrFail($request->parcour_id);

        if(!$request->semestre_id){
            $semestre=enCours()->semestre;
        }else{
            $semestre=Semestre::find($request->semestre_id);
        }


        return $this->operation->getListMoyenneParSemestre($parcour,$semestre);


    }


    public function getCritereAdmis(Parcour $parcour){


        return [
            'passant'=>$parcour->critere_admis()->where('type','passant')->first(),
            'redoublant'=>$parcour->critere_admis()->where('type','redoublant')->first()
        ];
    }

    public function setCritereSemestre(Request $request)
    {

        $request->validate([
            'logique'      => 'required',
            'type'         => 'required',
            'min_moyenne'  => 'required|numeric|between:0,20',
            'max_moyenne'  => 'nullable|numeric|between:0,20',
            'min_ue'       => 'nullable',
            'min_credit'   => 'nullable'
        ]);

        if(!in_array($request->type,['v','vpc'])){
            abort(400,"valeur du type doivent suivre le norme");
        }

        if(!in_array($request->logique,['ou','et'])){
            abort(400,"valeur du logique doivent suivre le norme");
        }

        $parcour=Parcour::findOrFail($request->parcour_id);

        $critere=$parcour
            ->critere_validations()
            ->where('annee_universitaire_id',annee()->id)
            ->where('semestre_id',enCours()->semestre->id)
            ->where('type',$request->type)
            ->first();

        if(!$critere){

            CritereValidation::create([
                'type'                   => $request->type,
                'logique'                => $request->logique,
                'min_ue'                 => $request->min_ue,
                'max_ue'                 => $request->max_ue,
                'min_moyenne'            => $request->min_moyenne,
                'max_moyenne'            => $request->max_moyenne,
                'min_credit'             => $request->min_credit,
                'max_credit'             => $request->max_credit,
                'annee_universitaire_id' => annee()->id,
                'parcour_id'             => $parcour->id,
                'semestre_id'            => enCours()->semestre->id
            ]);

        }else{

            $critere->update([
                'type'                   => $request->type,
                'logique'                => $request->logique,
                'min_ue'                 => $request->min_ue,
                'max_ue'                 => $request->max_ue,
                'min_moyenne'            => $request->min_moyenne,
                'max_moyenne'            => $request->max_moyenne,
                'min_credit'             => $request->min_credit,
                'max_credit'             => $request->max_credit,
            ]);

        }


        $parcourID=$parcour->id;

        Artisan::call("moyenne-resolution:pour-tous-etudiant-parcours $parcourID");


        return $this->getCritereValidations($parcour);

    }



    public function getCritereValidations(Parcour $parcour)
    {
        return CritereValidation::where('parcour_id',$parcour->id)
                ->where('annee_universitaire_id',annee()->id)
                ->where('semestre_id',enCours()->semestre->id)
                ->get();
    }



    public function ListeAdmis(Request $request){

        $request->validate([
            'logique'      => 'required|in:et,ou',
            'min_ue'       => 'required|integer',
            'min_moyenne'  => 'required|numeric|between:0,20',
            'min_credit'  => 'required|numeric',
            'parcour_id'  => 'required'
        ]);


        $parcour=Parcour::findOrFail($request->parcour_id);

        $critere=$parcour
            ->critere_admis()
            ->where('annee_universitaire_id',annee()->id)
            ->where('type','passant')
            ->first();

        $data_critere=[
            'type'=>'passant',
            'logique'=> $request->logique,
            'parcour_id'=> $parcour->id,
            'annee_universitaire_id'=> annee()->id,
            'min_ue' => (int)$request->min_ue,
            'min_moyenne' => (float)$request->min_moyenne,
            'min_credit' => (float)$request->min_credit,

        ];
        
        if(!$critere){
            CritereAdmis::create($data_critere);
        }else{
            unset($data_critere['parcour_id']);
            unset($data_critere['annee_universitaire_id']);
            $critere->update($data_critere);
        }


        $parcourId=$parcour->id;
        Artisan::call("set-critere:for-all-student-in-parcour $parcourId");
        
        $filter=$parcour->historiques()
            ->with([
                'etudiant',
                'etudiant.personne',
                'moyenne_ues',
                'moyenne_annee',
            ])
            ->where('annee_universitaire_id',annee()->id)
            ->whereHas('moyenne_annee',function($query){
                $query->whereIsAdmis(1);
            })
            ->get()->sortBy([
                ['etudiant.personne.nom','asc'],
                ['etudiant.personne.prenom','asc'],
            ]);



        return $filter;
    }


    public function FetchAdmis(Parcour $parcour){


        $critere=$parcour->critere_admis()
            ->where('annee_universitaire_id',annee()->id)
            ->where('type','passant')
            ->first();
        
        if(!$critere){
            abort(400,"Le critère de passage n'est pas encore disponible sur ce parcour");
        }

        $filter=$parcour->historiques()
            ->with([
                'etudiant',
                'etudiant.personne',
                'moyenne_ues',
                'moyenne_annee',
            ])
            ->where('annee_universitaire_id',annee()->id)
            ->whereHas('moyenne_annee',function($query){
                $query->whereIsAdmis(1);
            })
            ->get()->sortBy([
                ['etudiant.personne.nom','asc'],
                ['etudiant.personne.prenom','asc'],
            ]);



        return $filter;

    }

    public function FetchRedoublant(Parcour $parcour)
    {

        $critere=$parcour
            ->critere_admis()
            ->where('annee_universitaire_id',annee()->id)
                ->where('type','redoublant')
                ->first();
        
        if(!$critere){
            abort(400,"Le critère de passage n'est pas encore disponible sur ce parcour");
        }


        $filter=$parcour->historiques()
            ->with([
                'etudiant',
                'etudiant.personne',
                'moyenne_ues',
                'moyenne_annee',
            ])
            ->where('annee_universitaire_id',annee()->id)
            ->whereHas('moyenne_annee',function($query){
                $query->whereIsAdmis(0);
            })
            ->get()->sortBy([
                ['etudiant.personne.nom','asc'],
                ['etudiant.personne.prenom','asc'],
            ]);



        return $filter;
    }


    public function ListeRedoublants(Request $request){


        $request->validate([
            'logique'      => 'required|in:et,ou',
            'min_ue'       => 'nullable|integer',
            'max_ue'       => 'nullable|integer',
            'min_moyenne'  => 'nullable|numeric|between:0,20',
            'max_moyenne'  => 'nullable|numeric|between:0,20',
            'min_credit'   => 'nullable|numeric',
            'max_credit'   => 'nullable|numeric',
            'parcour_id'   => 'required'
        ]);
        
        $parcour=Parcour::findOrFail($request->parcour_id);

        $critere=$parcour
            ->critere_admis()
            ->where('annee_universitaire_id',annee()->id)
                ->where('type','redoublant')
                ->first();


        $data_critere=[
            'type'                   =>'redoublant',
            'logique'                =>$request->logique,
            'parcour_id'             => $parcour->id,
            'annee_universitaire_id' => annee()->id,
            'min_ue'                 => (int)$request->min_ue,
            'max_ue'                 => (int)$request->max_ue,
            'min_moyenne'            => (float)$request->min_moyenne,
            'max_moyenne'            => (float)$request->max_moyenne,
            'min_credit'             => (float)$request->min_credit,
            'max_credit'             => (float)$request->max_credit
        ];
        

        if(!$critere){
            CritereAdmis::create($data_critere);
        }else{
            unset($data_critere['parcour_id']);
            $critere->update($data_critere);
        }
        
        $parcourId=$parcour->id;

        Artisan::call("set-critere:for-all-student-in-parcour $parcourId");
        
        $filter=$parcour->historiques()
            ->with([
                'etudiant',
                'etudiant.personne',
                'moyenne_ues',
                'moyenne_annee',
            ])
            ->where('annee_universitaire_id',annee()->id)
            ->whereHas('moyenne_annee',function($query){
                $query->whereIsAdmis(0);
            })
            ->get()->sortBy([
                ['etudiant.personne.nom','asc'],
                ['etudiant.personne.prenom','asc'],
            ]);



        return $filter;
    }



    public function ImprimerPdf(Request $request){

        $request->validate(['parcour_id'=>'required']);

        $parcour=Parcour::findOrFail($request->parcour_id);

        if(!$request->semestre_id){
            $semestre=enCours()->semestre;
        }else{
            $semestre=Semestre::find($request->semestre_id);
        }

        $data['header']['parcour']=$parcour->parcour;
        $data['header']['annee']=annee()->valeur;
        $data['header']['semestre']=$semestre->semestre;
        $data['type']=$request->type;
        $data['liste']=$this->ValidationUe($request);

        $pdf=new ImpressionEtudiant;
        $pdf->GeneratePDF($data,'PDF.moyenne-ue');

        return \App\Models\Pdf::where('user_id',auth()->user()->id)->first();

    }

    public function ImprimerPdfDefinitive(Request $request){

        $request->validate(['parcour_id'=>' required']);

        $parcour=Parcour::findOrFail($request->parcour_id);

        $data['header']['parcour']=$parcour->parcour;
        $data['header']['annee']=annee()->valeur;
        $data['type']=$request->type;
        $data['liste']=$this->ValidationDefinitive();

        $pdf=new ImpressionEtudiant;

        return $pdf->GeneratePDF($data,'PDF.validation-definitive');

    }

    public function ImprimerPdfListeEtudiant(Parcour $parcour){

        $data['list']=$parcour->historiques;

        $data['header']['parcour']=$parcour->parcour;
        $data['header']['annee']=annee()->valeur;

        $pdf=new ImpressionEtudiant;

        return $pdf->GeneratePDF($data,'PDF.liste-etudinat');

    }


    public function setPublicUe(Ue $ue,Parcour $parcour,AnneeUniversitaire $annee=null,Semestre $semestre=null){
        

        if(!$annee){
            $annee=annee();
        }

        $semestre=$ue->semestre;

        if(
            UePublic::where('ue_id',$ue->id)
            ->where('annee_universitaire_id',$annee->id)
            ->where('parcour_id',$parcour->id)
            ->where('semestre_id',$semestre->id)
            ->first()
        )
            abort(400,"UE déja publier");


        if(UePublic::create([
            'ue_id'               =>  $ue->id,
            'parcour_id'          =>  $parcour->id,
            'semestre_id'           => $semestre->id,
            'annee_universitaire_id' =>  $annee->id
        ]))
            return ['success'=>'ok'];
        else
            abort(400,'erreur création de publication resultat ue');
    }



    public function setPublicUeRattrapage(Ue $ue,Parcour $parcour,AnneeUniversitaire $annee=null,Semestre $semestre=null){
        

        if(!$annee){
            $annee=annee();
        }

        $semestre=$ue->semestre;

        if(enCours()->session->id!=1)
            abort(400,"Action n'est pas encore disponible");

        $public= UePublic::where('ue_id',$ue->id)
            ->where('annee_universitaire_id',$annee->id)
            ->where('parcour_id',$parcour->id)
            ->where('semestre_id',$semestre->id)
            ->first();

        if(!$public){

            UePublic::create([
            'ue_id'                     =>  $ue->id,
            'parcour_id'                =>  $parcour->id,
            'semestre_id'               =>  $semestre->id,
            'annee_universitaire_id'    =>  $annee->id,
            'avec_ratrapage'            =>  1
            ]);
        }
        else
            $public->update(['avec_ratrapage'=>1]);


        return ['success'=>'ok'];
    }


    public function setPublicFinalResult(Parcour $parcour){

        $annee=annee();

        $critere=CritereAdmis::where('annee_universitaire_id',$annee->id)
            ->where('parcour_id',$parcour->id)
            ->first();
        if(!$critere){
            abort(400,"Critère admission n'est pas encore inserer");
        }

        if($parcour->has_final_result())
            abort(400,"Résultat déja publier");

        if(PublicFinal::create([
            'parcour_id'          =>  $parcour->id,
            'annee_universitaire_id' =>  $annee->id
        ]))
            return ['success'=>'ok'];
        else
            abort(400,'erreur création de publication resultat final');
    }

    public function setPublicSemestreResult(Parcour $parcour)
    {

        $public=SemestrePublic::where('parcour_id',$parcour->id)
                                ->where('annee_universitaire_id',annee()->id)
                                ->where('semestre_id',enCours()->semestre->id)
                                ->first();
        if($public)
            abort(400,'resultat déja publier');

        $critere_validation=$parcour->critere_validations()->where('annee_universitaire_id',annee()->id)
                                    ->where('semestre_id',enCours()->semestre->id)->first();

        if(!$critere_validation){
            abort(400,"Veuillez ajouter un critères de validation");
        }
        
        return SemestrePublic::create([
                    'parcour_id'               => $parcour->id,
                    'annee_universitaire_id'   => annee()->id,
                    'semestre_id'              => enCours()->semestre->id
                ]);

    }

    public function cancelPublicSemestreResult(Parcour $parcour)
    {

        $public=SemestrePublic::where('parcour_id',$parcour->id)
            ->where('annee_universitaire_id',annee()->id)
            ->where('semestre_id',enCours()->semestre->id)
            ->first();

        if($public)
            $public->delete();
        else
            abort(400,"résultat n'est pas encore publier");

        return [];
    }

    public function cancelResult(Ue $ue,Parcour $parcour,AnneeUniversitaire $annee=null,Semestre $semestre=null){


        if(!$annee){
            $annee=annee();
        }

        $semestre=$ue->semestre;

        $select=$ue->ue_publics()
            ->where('annee_universitaire_id',$annee->id)
            ->where('semestre_id',$semestre->id)
            ->where('parcour_id',$parcour->id);

        if(!$select->exists()){
            abort(400,"publication n'existe pas");
        }

        $deleted=$select->delete();
        
        if($deleted)
            return ['success'=>"delete"];
        
        return ['error'=>'delete'];

        
    }

    public function cancelResultRattrapage(Ue $ue,Parcour $parcour,AnneeUniversitaire $annee=null,Semestre $semestre=null){


        if(!$annee){
            $annee=annee();
        }

        $semestre=$ue->semestre;

        $select=$ue->ue_publics()
            ->where('annee_universitaire_id',$annee->id)
            ->where('semestre_id',$semestre->id)
            ->where('parcour_id',$parcour->id);

        if(!$select->exists()){
            abort(400,"publication session normal n'existe pas");
        }else{
            $select->update(['avec_ratrapage'=>0]);
        }

        
        return ['error'=>'delete'];

        
    }

    public function cancelFinalResult(Parcour $parcour)
    {
        $annee=annee();
        $select=PublicFinal::where('annee_universitaire_id',$annee->id)
                ->where('parcour_id',$parcour->id);

        if(!$select->exists()){
            abort(400,"publication n'existe pas");
        }

        $deleted=$select->delete();
        
        if($deleted)
            return ['success'=>"delete"];
        
        return ['error'=>'delete'];

    }


}
