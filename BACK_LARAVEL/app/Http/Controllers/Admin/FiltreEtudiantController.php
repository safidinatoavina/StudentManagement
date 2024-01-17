<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Filter\FilterService;
use App\Service\Filter\FilterEtudiant;


class FiltreEtudiantController extends Controller
{
    public function filterWithNote(Request $request)
    {

        $this->authorize('viewAny', Note::class);

        $filter=new FilterService;
        
        $filter->setQuery($request);

        $result=$filter->handle();

        return $result->toArray();
        
    }

    public function JuryFilterEtudiant(Request $request){

        $this->authorize('viewAny', Note::class);

        $request->validate(['parcour_id'=>'required']);

        $filter=new FilterService;


        $parcour_id=$request->parcour_id;
        
        if($parcour_id){
            $request->merge([
                'parcour_id'=>$parcour_id,
                'annee_universitaire_id'=>annee()->id,
            ]);
        }
        else
            abort(401,"action non autoriser contacter l'admin pour assigner votre parcour");
        
        $filter->setQuery($request);

        $result=$filter->handle();

        return $result->toArray();

    }

    public function filterEtudiant(Request $request){

        $this->authorize('viewAny', Note::class);

        $filter=new FilterEtudiant;
        $filter->setQuery($request);
        $result=$filter->handle();

        return $result->toArray();

    }
}
