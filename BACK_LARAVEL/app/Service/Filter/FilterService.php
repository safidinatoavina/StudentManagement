<?php

namespace App\Service\Filter;

use App\Models\Note;
use App\Models\Grade;
use App\Models\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterService{

    public $query;
    private $result;

    /**
     * 
     *  methode available :
     * 
     * @setQuery @param type Request
     * 
     * @handle  main of filtre
     * 
     */

    public function __construct()
    {
        $request=new Request;
        
        $request->merge(['annee_universitaire_id'=>annee()->id]);

        $this->query=$request;


    }

    public function setQuery(Request $data){

        $this->query->merge($data->all());

    }

    private function validateQuery(){


        if(!$this->query->numeroInscription){

            if(empty($this->query->parcours)){
                abort(400,"Le champ parcours est obligatoire pour optimiser la requête.");
            }else{

                if(!auth()->user()->roles()->where('roles.id',1)->exists()){

                    $parcours_id=auth()->user()->parcours->map(function($parcour){
                        return $parcour->id;
                    })->toArray();

                    $parcour_autorize=true;
    
                    foreach ($this->query->parcours as $parcour_id) {
                        if(!\in_array($parcour_id,$parcours_id))
                            $parcour_autorize=false;
                    }
    
                    if(!$parcour_autorize)
                        abort(400,"vous n'avez pas l'accès à l'un de ces parcours");
                }



            }
    
            if(!$this->query->session_id){
                abort(400,"Le champ session est obligatoire pour optimiser la requête.");
            }
    
            if(!$this->query->semestre_id){
                abort(400,"Le champ semestre est obligatoire pour optimiser la requête.");
            }
        }


    }


    private function prepareResult(){

        //validation pour optimisation requette

        $this->validateQuery();

        //default result

        $this->result=Note::with(['historique','historique.parcour','historique.status','historique.annee_universitaire','historique.etudiant','historique.etudiant.personne','session','semestre','matiere'])
                    ->whereHas('historique',function($historiques){
                        $historiques->where('historiques.annee_universitaire_id',$this->query->annee_universitaire_id);
                    });
        //---------------------------||--------------------

        $this->whereNote();
        $this->whereParcour();
        $this->whereStatus();
        $this->whereSession();
        $this->whereSemestre();
        $this->whereMatiers();
        $this->whereNumInscription();

    }

    private function whereNote(){

        //filtre via note active

        if(!$this->query->signe_note)
            return;

        $this->result=$this->result->where('valeur',$this->query->signe_note,$this->query->note);

    }

    private function whereParcour(){

        //filtre via parcours active

        if(!$this->query->parcours)
            return;

        $this->result=$this->result->whereHas('historique',function($historique){
            $historique->whereIn('historiques.parcour_id',$this->query->parcours);
        });

    }


    private function whereMatiers(){

        //filtre via matiers active

        if(!$this->query->matiers)
            return;

        $this->result=$this->result->whereIn('matiere_id',$this->query->matiers);
    }

    private function whereNumInscription(){

        //filtre via numero d'inscription active

        if(!$this->query->numeroInscription)
            return;

        $this->result=$this->result->whereHas('historique',function($historique){
            $historique->where('historiques.numeroInscription',$this->query->numeroInscription);
        });

    }

    private function whereStatus(){
        
        //filtre via status active

        if(!$this->query->status_id)
            return;
        

        $this->result=$this->result->whereHas('historique',function($historique){
            $historique->where('historiques.status_id',$this->query->status_id);
        });

    }

    private function whereSession(){
        
        //filtre via session active

        if(!$this->query->session_id)
            return;

        $this->result=$this->result->whereHas('session',function($session){
            $session->where('sessions.id',$this->query->session_id);
        });

    }


    private function whereSemestre(){
        
        //filtre via semestre active

        if(!$this->query->semestre_id)
            return;

        $this->result=$this->result->whereHas('semestre',function($query){
            $query->where('semestres.id',$this->query->semestre_id);
        });

    }


    public function handle(){

        $this->prepareResult();

        return $this->result
                    ->orderBy('notes.matiere_id','asc')
                    ->get();        
    }




}


