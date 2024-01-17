<?php

namespace App\Imports;

use App\Models\Status;
use App\Models\Parcour;
use App\Models\Personne;
use App\Models\Historique;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\Etudiant\EtudiantController;

class EtudiantImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {

        //verification

        $this->validate($rows);

        //traitement

        $this->import($rows);

    }

    public function validate($rows){

        foreach ($rows as $key => $etudiant) {
            if ($key==0) {
                continue;
            }

            if(empty($etudiant[0])&&empty($etudiant[1])&&empty($etudiant[2])&&empty($etudiant[3])){
                continue;
            }

            $status=Status::where('abreviation',trim($etudiant[8]))->first();
            $parcour=Parcour::where('abreviation',trim($etudiant[7]))->first();
            $num_inscription=Historique::where('numeroInscription',trim($etudiant[6]))->first();
            $cin=Personne::where('cin',trim($etudiant[5]))->first();

            $line=$key+1;

            if($cin)
                abort(400,"CIN déja pris line $line");

            if($num_inscription)
                abort(400,"numero d'inscription déja pris line $line");

            if(!$parcour)
                abort(400,"parcour (".$etudiant[7].") introuvable line $line");
            
            if(!$status)
                abort(400,"status (".$etudiant[8].") introuvable line $line corriger par (P ou R)");
        }

    }

    public function import($rows){

        $EtudiantController=new EtudiantController;

        foreach ($rows as $key => $etudiant) {

            if($key==0)
                continue;
                
            if(empty($etudiant[0])&&empty($etudiant[1])&&empty($etudiant[2])&&empty($etudiant[3])){
                continue;
            }
                
            $status=Status::where('abreviation',$etudiant[8])->first();
            $request=request();
            $request->merge([
                'nom'=>$etudiant[0],
                'prenom'=>$etudiant[1],
                'date_naissance'=>$etudiant[2],
                'lieu_naissance'=>$etudiant[3],
                'address'       =>$etudiant[4],
                'cin'           =>$etudiant[5],
                'numeroInscription' =>$etudiant[6],
                'parcour_id'        =>Parcour::where('abreviation',$etudiant[7])->first()->id,
                'status_id'         =>$status?$status->id:1,
            ]);


            $EtudiantController->store($request);

        }

    }
}
