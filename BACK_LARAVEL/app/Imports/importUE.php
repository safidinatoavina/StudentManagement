<?php

namespace App\Imports;

use App\Models\Ue;
use App\Models\Parcour;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\data_faculte\DataFaculteController;

class importUE implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $dataController=new DataFaculteController;

        $this->validate($rows);

        foreach ($rows as $key => $value) {

            if(!$key)
                continue;

            if(empty($value[0])&&empty($value[1])&&empty($value[2])&&empty($value[3]))
                continue;
            
            $parcour=Parcour::where('parcour',$value[3])->first();

            if(!$parcour)
                abort(400,"parcour (".$value[3].") introuvable");
            
            Ue::create([
                'ue'          => $value[0],
                'coefficient' => $value[1],
                'credit'      => $value[2],
                'parcour_id'  => $parcour->id
            ]);

        }
    }

    public function validate($rows)
    {
        foreach ($rows as $key => $value) {

            if(!$key)
                continue;

            if(empty($value[0])&&empty($value[1])&&empty($value[2])&&empty($value[3]))
                continue;

            $line=$key+1;

            if(!$value[0])
                abort(400,"le champ ue est obligatoire $line");
            if(!$value[1])
                abort(400,"le champ Coefficient est obligatoire $line");
            if(!$value[2])
                abort(400,"le champ Credit obligatoire $line");

            $parcour=Parcour::where('parcour',$value[3])->first();
            if(!$parcour)
                abort(400,"parcour (".$value[3].") introuvable, line $line");
            
        }
    }
}
