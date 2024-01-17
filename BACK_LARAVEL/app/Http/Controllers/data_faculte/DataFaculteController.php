<?php

namespace App\Http\Controllers\data_faculte;



use Illuminate\Http\Request;
use App\Models\{Ue,Grade,EnCour,Status,Matiere,TP,
        Mention,Parcour,Session,
        Semestre,AnneeUniversitaire,User,Note,
        MoyenneMatiere,MoyenneUe,MoyenneSemestre,
        MoyenneAnnee,NoteTp,NombreUeOptionObligatoir
    };
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Service\Moyenne\MoyenneService; 
use App\Service\Cache\SystemeCache;


class DataFaculteController extends Controller
{


    public function all(){

        $cache=new SystemeCache;
        
        return [
            "mentions" => $cache->mentions(),
            "grades"   => $cache->grades(),
            "parcours" => $cache->parcours(),
            "annees"   => $cache->annees(),
            "ues"      => $cache->ues(),
            "matiers"  => $cache->matiers(),
            'en_cours' => $this->getActive(),
            'semestres'=> DB::table('semestres')->orderBy('id','desc')->get(),
            'sessions' => DB::table('sessions')->orderBy('id','desc')->get(),
            'status'   => DB::table('statuses')->get(),
            'tps'      => $cache->tps()
        ];
    }

    public function getParcourJury(User $jury){
        return $jury->parcours;
    }

    public function mentions(){
        return Mention::all()->toArray();
    }


    public function grades(){
        return Grade::all()->toArray();
    }

    public function parcours(){
        return Parcour::with(['mention','grade','jury'=>function($query){
            $query->with(['personne']);
            },
            'user_responsables'=>function($responsable){
                $responsable->with('personne');
            }
        ])->orderBy('parcour')->get()->toArray();
    }



    public function annees(){
        return AnneeUniversitaire::orderBy('id','desc')->get()->toArray();
    }

    public function ues(){
        return Ue::with(['parcour','semestre'])->get()->toArray();
    }

    public function matiers(){
        return Matiere::with(['professeur.personne','ue','parcour','semestre'])->orderBy('matiere')->get()->toArray();
    }

    public function tps(){
        return TP::with(['professeur.personne','matiere','matiere.semestre','matiere.parcour'])->get()->toArray();
    }

    public function addParcour(Request $request)
    {
        $request->validate([
            'grade_id'=>"required",
            'mention_id'=>"required",
            "parcour"   => "required|unique:parcours",
        ]);

        $parcour= Parcour::create($request->except(['jury_id','responsable_id']));
        

        if($request->jury_id){
            $parcour->jury()->sync([$request->jury_id]);
        }

        if($request->responsable_id){
            $parcour->user_responsables()->sync([$request->responsable_id]);
        }

        return $parcour->with(['mention','grade','jury'=>function($query){
            $query->with(['personne']);
        },
        'user_responsables'=>function($responsable){
            $responsable->with('personne');
        }
        ])->where('id',$parcour->id)->first();
    }

    public function updateParcour(Request $request,Parcour $parcour)
    {

        if($request->jury_id){
            $parcour->jury()->sync([$request->jury_id]);
        }else{
            $parcour->jury()->sync([]);
        }

        if($request->responsable_id){
            $parcour->user_responsables()->sync([$request->responsable_id]);
        }else{
            $parcour->user_responsables()->sync([]);
        }

        $parcour->update($request->only(['parcour','abreviation']));

        return Parcour::with(['mention','grade','jury'=>function($query){
            $query->with(['personne']);
            },
            'user_responsables'=>function($responsable){
                $responsable->with('personne');
            }
        ])->where('id',$parcour->id)->first();
    }

    public function addMention(Request $request){
        $request->validate([
            'mention'=>'required',
        ]);
        return Mention::create($request->only(['mention','abreviation']));
    }

    public function updateMention(Request $request,Mention $mention){

        $request->validate([
            'mention'=>'required',
        ]);

        return $mention->update($request->all());

    }

    public function addGrade(Request $request)
    {
        $request->validate([
            'grade'=>'required',
            'niveau'=> 'required'
        ]);

        return Grade::create($request->all());
    }

    public function addTP(Request $request){

        $validated=$request->validate([
            'tp'         => 'required',
            'matiere_id' => 'required',
            'user_id'    => 'required'
        ]);

        $matiere=Matiere::with('tp')->find($request->matiere_id);

        if($matiere->tp)
            abort(400,"matière à été déjà un tp");

        $new_tp=TP::create($validated);



        $historiques=$matiere->parcour->historiques()
                ->where('annee_universitaire_id',annee()->id)
                ->get();

        $MoyenneService=new MoyenneService;

        foreach ($historiques as $historique) {
            NoteTp::create([
                'tp_id' =>$new_tp->id,
                'historique_id' => $historique->id
            ]);

            $MoyenneService->updateMoyenne($matiere,$historique);
        }


        return TP::with(['professeur.personne','matiere','matiere.semestre','matiere.parcour'])->find($new_tp->id);

    }


    public function updateTP(Request $request,TP $tp){

        $validated=$request->validate([
            'tp'         => 'required',
            'user_id'    => 'required'
        ]);

        $tp->update($validated);

        return TP::with(['professeur.personne','matiere','matiere.semestre','matiere.parcour'])->find($tp->id);

    }



    public function addAnnee(Request $request)
    {
        $request->validate([
            'valeur'=>'required|unique:annee_universitaires'
        ]);

        $annee=AnneeUniversitaire::create($request->only('valeur'));

        return $this->annees();
    }

    public function updateStatutAnnee(Request $request,AnneeUniversitaire $annee){

        $request->validate([
            'statut'=> 'required'
        ]);

        $_annee=AnneeUniversitaire::where('statut',1)->orderBy('id','desc')->first();

        if($_annee){
            if(annee()->id==$annee->id){
                if($request->statut!=='1')
                    abort(400,"Il faut une annee universitaire actif");
            }
        }



        if($request->statut==1){
            //si l'admin veux activer une année
            //restorer la publication de resultat
            //changer le statut actif en terminé
            AnneeUniversitaire::where('statut',1)->update(['statut'=>'-1']);
        }


        $annee->update(['statut'=>$request->statut]);

        return [
           "annees" => $this->annees(),
           "en_cours" => $this->getActive()
        ];
        
    }


    private function autorize_add(){
        $is_ok = auth()->user()->roles()->where('roles.id',6)->exists() || auth()->user()->roles()->where('roles.id',1)->exists();
        if(!$is_ok)
            abort(400,"Action n'est pas autorisé");
    }

    public function addUe(Request $request){

        $this->autorize_add();

        $validate=[
            'ue'          => 'required',
            'credit'      => 'required|numeric',
            'parcour_id'  => 'required|integer',
            'semestre_id' => 'required|integer',
            'option'      => 'required'
        ];
        
        $request->merge(['coefficient'=>($request->credit/2)]);

        $request->validate($validate);

        $ue=Ue::create($request->all());

        return Ue::with(['parcour','semestre'])->find($ue->id);
    }

    public function addMatiere(Request $request){

        $this->autorize_add();

        $validate=[
            'coefficient'   => 'required|numeric',
            'user_id'       => 'required',
            'ue_id'         => 'required',
            'matiere'       => 'required'
        ];

        $ue=Ue::findOrFail($request->ue_id);

        $request->merge(['parcour_id'=>$ue->parcour->id]);

        $request->validate($validate);

        $request->merge(['semestre_id'=>Ue::find($request->ue_id)->semestre_id]);

        $matiere = Matiere::create($request->all());

        $this->createDefaultNote(Parcour::findOrFail($request->parcour_id),$matiere);

        return $matiere->with(['professeur.personne','ue','parcour','semestre'])
                       ->where('id',$matiere->id)
                       ->first();

    }


    public function editMatiere(Request $request,Matiere $matiere)
    {
        $request->validate([
            'coefficient'   => 'required|numeric',
            'user_id'       => 'required',
            'matiere'       => 'required'
        ]);

        $last_coefficient=$matiere->coefficient;

        $matiere->update($request->all());

        //reslve moyenne en cas de moddififcation de coefficient

        if($last_coefficient!=$request->coefficient){

            $matiere->moyenne_matieres()
                ->whereHas('historique',function($historique_query){
                    $historique_query->where('annee_universitaire_id',annee()->id);
                })
                ->update(['default_coefficient'=>$request->coefficient]);

            $historiques=$matiere->parcour->historiques()->where('annee_universitaire_id',annee()->id)->get();

            $MoyenneService=new MoyenneService;

            foreach ($historiques as $key => $historique) {
                $MoyenneService->updateMoyenne($matiere,$historique);
            }

        }

        return Matiere::with(['professeur.personne','ue','parcour','semestre'])->find($matiere->id);

    }

    public function editUE(Request $request,Ue $ue)
    {
        $request->validate([
            'credit'       => 'required',
            'ue'       => 'required'
        ]);

        $request->merge(['coefficient'=>($request->credit)]);

        $last_credit      = $ue->credit;
        $last_coefficient = $ue->coefficient;

        $ue->update($request->all());

        if($last_credit!=$request->credit || $last_coefficient!=$request->coefficient){
            
            $ue->moyenne_ues()
                ->whereHas('historique',function($historique_query){
                    $historique_query->where('annee_universitaire_id',annee()->id);
                })
                ->update(['default_coefficient'=>$request->coefficient,'default_credit'=>$request->credit]);

            $historiques=$ue->parcour->historiques()->where('annee_universitaire_id',annee()->id)->get();

            $MoyenneService=new MoyenneService;

            foreach ($historiques as $key => $historique) {
                foreach ($ue->matieres as $key => $matiere) {
                    $MoyenneService->updateMoyenne($matiere,$historique);
                }
            }

        }

        return Ue::with(['parcour','semestre'])->find($ue->id);

    }

    public function updateStatusMatiere(Request $request,Matiere $matiere)
    {
        $request->validate(['status'=>'required|min:0|max:1']);
        $matiere->update(['status'=>$request->status]);

        return ['success'];
    }

    public function GestionActive(Request $request){

        $request->validate([
            'semestre_id'           => 'required|integer',
            'session_id'            => 'required|integer'
        ]);


        EnCour::updateOrCreate(
            ['id'=>$request->id],
            ['semestre_id'=>$request->semestre_id,'session_id'=>$request->session_id],
        );

        return $this->getActive();

    }

    public function getActive(){

        $en_cour = EnCour::with('semestre','session')->orderBy('id','desc')->first();
        if(!$en_cour)
            return ['annee'=>annee()];
        
        return $en_cour;

    }

    public function deleteMention(Mention $mention){
        return $mention->delete();
    }

    public function deleteGrade(Grade $grade){
        return $grade->delete();
    }

    public function deleteParcour(Parcour $parcour){
        return $parcour->delete();
    }

    public function deleteAnnee(AnneeUniversitaire $annee){
        return $annee->delete();
    }


    public function deleteUe(Ue $ue){
        $this->autorize_add();
        
        $MoyenneService=new MoyenneService(); 

        $parcour=$ue->parcour;
        $historiques=$parcour->historiques()->where('annee_universitaire_id',annee()->id)->get();

        $ue->delete();


                
        foreach ($historiques as $key => $historique) {
            foreach ($parcour->matieres as $matiere) {
                $MoyenneService->updateMoyenne($matiere,$historique);
            }
        }

        return true;

    }

    public function deleteMatiere(Matiere $matiere)
    {

        $MoyenneService=new MoyenneService(); 

        $this->autorize_add();

        $parcour=$matiere->parcour;
        $historiques=$parcour->historiques()->where('annee_universitaire_id',annee()->id)->get();

        
        $matiere->delete();
        
        foreach ($historiques as $key => $historique) {
            foreach ($parcour->matieres as $matiere) {
                $MoyenneService->updateMoyenne($matiere,$historique);
            }
        }

        return true;
    }

    public function deleteTP(TP $tp)
    {
        $this->autorize_add();
        $matiere=$tp->matiere;
        $tp->delete();

        $MoyenneService=new MoyenneService(); 

        $historiques=$matiere->parcour->historiques()->where('annee_universitaire_id',annee()->id)->get();

        foreach ($historiques as $key => $historique) {
            $MoyenneService->updateMoyenne($matiere,$historique);
        }


        return true;
    }

    public function setNombreUesObli(Request $request){

        $request->validate(['parcour_id'=>'required','semestre_id'=>'required']);

        $parcour=Parcour::findOrFail($request->parcour_id);
        $semestre=Semestre::findOrFail($request->semestre_id);

        $ue_option=$parcour->ues()->where('semestre_id',$semestre->id)
                            ->where('option',1)->count();

        if(!$ue_option)
            abort(400,"cette parcour n'a pas d'UE optionel pour cette semestre");

        $request->validate(['nombre_ue_obli'=>"required|numeric|min:1|max:$ue_option"]);


        $nombre_ue_option_obligatoires=$parcour->nombre_ue_option_obligatoires()
                ->where('semestre_id',$semestre->id)
                ->first();


        if($nombre_ue_option_obligatoires){
            $nombre_ue_option_obligatoires->update(['nombre_ue_obli'=>$request->nombre_ue_obli]);
        }else{

            NombreUeOptionObligatoir::create([
                'nombre_ue_obli'=> $request->nombre_ue_obli,
                'semestre_id'   => $semestre->id,
                'parcour_id'    => $parcour->id,
            ]);
        }

        $MoyenneService=new MoyenneService;

        $matieres=$parcour->matieres()->where('semestre_id',$semestre->id)->get();
        $historiques=$parcour->historiques()->where('annee_universitaire_id',annee()->id)->get();

        foreach ($matieres as $key => $matiere) {
            foreach($historiques as $historique){

                $MoyenneService->updateMoyenne($matiere,$historique);

            }
        }


        return $parcour->nombre_ue_option_obligatoires()->with('semestre')->get();


    }


    public function getNombreUesObli(Request $request){

        $request->validate(['parcour_id'=>'required']);

        $parcour=Parcour::findOrFail($request->parcour_id);


        return $parcour->nombre_ue_option_obligatoires()->with('semestre')->get();


    }


    public function createDefaultNote(Parcour $parcour,Matiere $matiere){


        $historiques=$parcour->historiques()
                        ->where('annee_universitaire_id',annee()->id)
                        ->get();

        foreach (Session::all() as $session) {

            foreach ($historiques as $historique) {

                Note::create([
                    'historique_id'=>$historique->id,
                    'matiere_id'   => $matiere->id,
                    'semestre_id'  => $matiere->semestre->id,
                    'session_id'   => $session->id,
                    'valeur'       =>0,
                    'is_set'       =>0
                ]);

            }

        }


        foreach ( $historiques as $historique) {

            MoyenneMatiere::create([
                'valeur'=>0,
                'matiere_id'=>$matiere->id,
                'historique_id'=>$historique->id,
                'default_coefficient'   => $matiere->coefficient
            ]);

            if($matiere->tp){
                NoteTp::create([
                    'tp_id' =>$matiere->tp->id,
                    'historique_id' => $historique->id
                ]);
            }

        }



        $ue=$matiere->ue;

        $moyenne_ue=MoyenneUe::where('ue_id',$ue->id)
                ->where('semestre_id',$matiere->semestre->id)
                ->first();

        if(!$moyenne_ue){

            foreach ($historiques as $historique) {
    
                MoyenneUe::create([
                    'valeur'                => 0,
                    'ue_id'                 => $ue->id,
                    'semestre_id'           => $matiere->semestre->id,
                    'historique_id'         => $historique->id,
                    'default_credit'        => $ue->credit,
                    'default_coefficient'   => $ue->coefficient
                ]);

            }

        }




        $MoyenneService = new MoyenneService;

        foreach ($historiques as $historique) {

            $MoyenneService->updateMoyenne($matiere,$historique);

        }



    }


}
