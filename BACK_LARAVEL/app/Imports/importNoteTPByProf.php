<?php

namespace App\Imports;

use App\Models\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\Admin\NoteController;

class importNoteTPByProf implements ToCollection
{
    /**
    * @param Collection $collection
    */

    private $data;
    private $tp;
    private $historiques;

    public function __construct($tp){

        $this->tp=$tp;

        //garder cette resultat pour eviter une redondance de requete
        $this->historiques=Historique::with(['note_tps'=>function($note_query){
            $note_query->where('note_tps.tp_id',$this->tp->id);
        }])
        ->where('parcour_id',$this->tp->matiere->parcour_id)
        ->whereHas('note_tps',function($note_query){

            $note_query->where('note_tps.is_set',0)
            ->where('note_tps.tp_id',$this->tp->id);

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
                $this->validateAvecStatut();
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

    private function validateAvecStatut(){


        foreach ($this->data as $key => $historique) {

            $line=$key+1;

            if($key==0 || trim($historique[3])==='' || trim($historique[0])=='N°INSCRIPTION')
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
            if($key==0 || trim($historique[3])==='' || trim($historique[0])=='N°INSCRIPTION')
                continue;
            
            $request=new Request;


            $find_historique=$this->historiques->where('numeroInscription',trim($historique[0]))->first();

            $request->merge([
                'id'            => $find_historique->note_tps->first()->id,
                'historique_id' => $find_historique->id,
                'tp_id'    => $this->tp->id,
                'valeur'        => (float)str_replace(',','.',trim($historique[3]))
            ]);

            $noteController->storeTP($request);

        }

    }

    public function importNoteAvecStatut(){

        $noteController=new NoteController;

        foreach ($this->data as $key => $historique) {
            if($key==0 || trim($historique[4])==='' || trim($historique[0])=='N°INSCRIPTION')
                continue;
            
            $request=new Request;


            $find_historique=$this->historiques->where('numeroInscription',trim($historique[0]))->first();

            $request->merge([
                'id'            => $find_historique->note_tps->first()->id,
                'historique_id' => $find_historique->id,
                'tp_id'    => $this->tp->id,
                'valeur'        => (float)str_replace(',','.',trim($historique[4]))
            ]);

            $noteController->storeTP($request);

        }

    }
}
