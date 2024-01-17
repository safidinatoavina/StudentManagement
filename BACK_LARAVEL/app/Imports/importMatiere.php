<?php

namespace App\Imports;

use App\Models\Ue;
use App\Models\Matiere;
use App\Models\Parcour;
use App\Models\Personne;
use App\Models\Semestre;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\data_faculte\DataFaculteController;

class importMatiere implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        
        $dataController=new DataFaculteController;

        $this->validate($rows);

        $this->importData($rows);


    }


    private function importData($rows)
    {
        foreach ($rows as $key => $value) {

            if(!$key)
                continue;

            if(empty($value[0])&&empty($value[1])&&empty($value[2])&&empty($value[3]))
                continue;

            $info_user=\explode(' ',$value[2]);

            $personne=Personne::where('nom',trim($info_user[0]));

            if(!empty($info_user[1]))
            {
                $personne=$personne->where('prenom',trim($info_user[1]));
            }

            $parcour=Parcour::where('parcour',trim($value[1]))->first();

            $personne=$personne->first();

            $pattern = "/^(.*?)\s*\((.*?)\)$/";
            $string=trim($value[3]);
            preg_match($pattern, $string, $matches);

            $ue=Ue::where('ue',trim($matches[1]))->where('parcour_id',$parcour->id)->first();

            Matiere::create([
                'matiere'       => trim($value[0]),
                'parcour_id'    => $parcour->id,
                'user_id'       => $personne->user->id,
                'ue_id'         => $ue->id,
                'semestre_id'   => Semestre::where('semestre',trim($value[4]))->first()->id,
                'coefficient'   => trim($value[5])
            ]);
        }
    }


    private function validate($rows)
    {


        foreach ($rows as $key => $value) {

            if(!$key)
                continue;

            if(empty($value[0])&&empty($value[1])&&empty($value[2])&&empty($value[3]))
                continue;
            
            $line=$key+1;

            if(empty($value[0]))
                abort(400,"le champ matiere est obligatoire $line");
            if(empty($value[1]))
                abort(400,"le champ parcour est obligatoire $line");
            $parcour=Parcour::where('parcour',$value[1])->first();
            if(!$parcour)
                abort(400,"parcour (".$value[1].") introuvable, line $line");
            
            $info_user=\explode(" ",trim($value[2]));
            
            $personne=Personne::where('nom',trim($info_user[0]));

            if(!empty($info_user[1]))
            {
                $personne=$personne->where('prenom',trim($info_user[1]));
            }

            $personne=$personne->first();

            if(!$personne)
                abort(400,"Professeur '".$value[2]."' introuvable , line $line");

            if(!$personne->user)
                abort(400,"'".$value[2]."'"."n'est pas un professeur line $line");

            if(empty($value[3]))
                abort(400,"le champ ue est obligatoire $line");

            $pattern = "/^(.*?)\s*\((.*?)\)$/";
            $string=trim($value[3]);
            preg_match($pattern, $string, $matches);

            if(empty($matches))
                abort(400,"le champ ue est invalide $line");

            $ue=Ue::with('parcour')->where('ues.ue',trim($matches[1]))->whereHas('parcour',function($query) use($matches){
                $query->where('parcours.parcour',trim($matches[2]));
            })->first();


            if(!$ue || $ue->parcour->id!=$parcour->id)
                abort(400,"Ue introuvable ou ue n'existe pas dans le parcour (".$parcour->parcour.") line $line");

            if(empty($value[4]))
                abort(400,"le champ semstre du matiere obligatoire $line");
            
            $semestre=Semestre::where('semestre',trim($value[4]))->first();

            if(!$semestre)
                abort(400,"le champ semstre est introuvable $line");

            if(empty($value[5]) || !is_numeric($value[5]))
                abort(400,"le champ coefficient doivent un nombre, line $line");

        }

    }

}
