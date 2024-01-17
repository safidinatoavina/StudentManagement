<?php

namespace App\Imports;

use App\Models\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\Admin\NoteController;

class importNoteByProf implements ToCollection
{
    /**
    * @param Collection $collection
    */

    private $data;
    private $matiere;
    private $historiques;

    public function __construct($matiere){

        $this->matiere=$matiere;

        //garder cette resultat pour eviter une redondance de requete
        $this->historiques=Historique::with(['notes'=>function($note_query){
            $note_query->where('notes.matiere_id',$this->matiere->id)->where('session_id',enCours()->session->id);
        }])
        ->where('parcour_id',$this->matiere->parcour_id)
        ->whereHas('notes',function($note_query){

            $note_query->where('is_set',0)
            ->where('matiere_id',$this->matiere->id)
            ->where('session_id',enCours()->session->id);

        })->get();
    }

    public function collection(Collection $collection)
    {
        $this->data=$collection;
        $this->validate();
        $this->importNote();
    }

    private function validate(){

        if(count($this->data)>=2 && !empty($this->data[1][4])){
            if($this->data[1][3]=='STATUT'){
                $this->validateAvecSTatut();
                return;
            }
        }

        foreach ($this->data as $key => $historique) {

            $line=$key+1;

            if($key==0 || trim($historique[3])==='' || trim($historique[0])=='N°INSCRIPTION')
                continue;

            $find_historique=$this->historiques->where('numeroInscription',trim($historique[0]))->count();

            if(!$find_historique)
            {
                abort(400,"l'etudiant de N°inscription (".$historique[0].") est introuvable dans le liste d'accès au ajout note line $line dans excel");
            }

            if((float)trim($historique[3])<0 || (float)trim($historique[3])>20 || !is_numeric(str_replace(',','.',trim($historique[3]))) )
                abort(400,"Le note sur le  line $line dans excel est invalide");


        }

    }

    private function validateAvecSTatut(){

        foreach ($this->data as $key => $historique) {

            $line=$key+1;

            if($key==0 || trim($historique[4])==='' || trim($historique[0])=='N°INSCRIPTION')
                continue;

            $find_historique=$this->historiques->where('numeroInscription',trim($historique[0]))->count();

            if(!$find_historique)
            {
                abort(400,"l'etudiant de N°inscription (".$historique[0].") est introuvable dans le liste d'accès au ajout note line $line dans excel");
            }

            if((float)trim($historique[4])<0 || (float)trim($historique[4])>20 || !is_numeric(str_replace(',','.',trim($historique[4]))) )
                abort(400,"Le note sur le  line $line dans excel est invalide");


        }

    }


    public function importNote(){
        
        if(count($this->data)>=2 && !empty($this->data[1][4])){
            if($this->data[1][3]=='STATUT'){
                $this->importNoteAvecStatut();
                return;
            }
        }

        $noteController=new NoteController;

        foreach ($this->data as $key => $historique) {
            if($key==0 || trim($historique[3])==='' || trim($historique[0])=='N°INSCRIPTION' )
                continue;
            
            $request=new Request;


            $find_historique=$this->historiques->where('numeroInscription',trim($historique[0]))->first();

            $request->merge([
                'id'            => $find_historique->notes->first()->id,
                'historique_id' => $find_historique->id,
                'matiere_id'    => $this->matiere->id,
                'valeur'        => (float)str_replace(',','.',trim($historique[3]))
            ]);

            $noteController->store($request);

        }

    }




    public function importNoteAvecStatut(){
        

        $noteController=new NoteController;

        foreach ($this->data as $key => $historique) {
            if($key==0 || trim($historique[4])==='' || trim($historique[0])=='N°INSCRIPTION' )
                continue;
            
            $request=new Request;


            $find_historique=$this->historiques->where('numeroInscription',trim($historique[0]))->first();

            $request->merge([
                'id'            => $find_historique->notes->first()->id,
                'historique_id' => $find_historique->id,
                'matiere_id'    => $this->matiere->id,
                'valeur'        => (float)str_replace(',','.',trim($historique[4]))
            ]);

            $noteController->store($request);

        }

    }

    

}
