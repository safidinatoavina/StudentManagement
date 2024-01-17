<?php

namespace App\Service\Filter;

use App\Models\Historique;
use Illuminate\Http\Request;

class FilterEtudiant{

    public $query;
    private $result;

    public function __construct()
    {
        $request=new Request;
        $this->query=$request;

    }


    public function setQuery(Request $data){

        $this->query->merge([...$data->all()]);

    }

    private function prepareResult(){

        $this->result=Historique::with(['parcour','etudiant',
        'etudiant.personne','status',
        'annee_universitaire',
        'notes'=>function($query){
            $query->with(['session','semestre','matiere']);
        }]);

        //$this->whereAnneeUniversitaire();
        $this->whereNumInscription();
        // $this->whereNom();
        // $this->wherePrenom();

    }

    private function whereNumInscription(){

        $this->result=$this->result->where('numeroInscription',$this->query->numeroInscription);
    }

    private function whereAnneeUniversitaire(){

        if (!$this->query->annee_universitaire_id) {
            return;
        }

        $this->result=$this->result->whereHas('annee_universitaire', function ($query) {
            $query->where('id', $this->query->annee_universitaire_id);
        });

    }

    private function whereNom(){

        if(!$this->query->nom)
            return;

        $this->result=$this->result->whereHas('etudiant',function($query){
            $query->whereHas('personne',function($personne){
                $personne->where('nom',$this->query->nom);
            });
        });

    }

    private function wherePrenom(){

        if(!$this->query->prenom)
            return;

        $this->result=$this->result->whereHas('etudiant',function($query){
            $query->whereHas('personne',function($personne){
                $personne->where('prenom',$this->query->prenom);
            });
        });
        
    }

    public function handle(){

        $this->prepareResult();

        return $this->result
            ->orderBy('annee_universitaire_id','desc')
            ->get();

        

    }




}

