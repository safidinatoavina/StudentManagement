<?php

namespace App\Http\Controllers\responsable;

use App\Models\Ue;
use App\Models\Parcour;
use App\Models\UeOption;
use App\Models\Historique;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Moyenne\MoyenneService;

class ResponsableController extends Controller
{
    public function getUeFacult(Parcour $parcour){



        if(!$parcour)
            abort(400,"Vous n'avez pas encore une parcour assigné");
            // ->ues()
            // ->whereOption(1)->get();


        return [
            'options'   => $parcour->ues()
                                ->whereOption(1)
                                ->get()
                                ->map(function($item){
                                    return [
                                        'ue'    => $item->ue,
                                        'ue_id' => $item->id
                                    ];
                                }),
            'etudiants' => $parcour->historiques()
                                ->with('ue_options')
                                ->where('annee_universitaire_id',annee()->id)
                                ->get()
                                ->map(function($item){
                                    return [
                                        'historique_id'=>$item->id,
                                        'numeroInscription'   =>$item->numeroInscription,
                                        'nom'                 =>$item->etudiant->personne->nom,
                                        'prenom'              =>$item->etudiant->personne->prenom,
                                        'ue_options'          =>$item->ue_options
                                    ];
                                })
        ];

    }

    public function setUeObli(Request $request)
    {

        $request->validate([
            'historique_id'=>'required',
            'ue_id'=>"required"
        ]);

        $historique=Historique::findOrFail($request->historique_id);
        $parcour=$historique->parcour;
        $ue=Ue::findOrFail($request->ue_id);
        $MoyenneService=new MoyenneService;

        $ue_option=UeOption::where('historique_id',$historique->id)
                            ->where('parcour_id',$parcour->id)
                            ->where('ue_id',$ue->id)
                            ->first();
        if(!(!!$ue_option)){

            UeOption::create([
                'historique_id' => $historique->id,
                'parcour_id'    => $parcour->id,
                'ue_id'        =>  $ue->id
            ]);

        }else{

            $MoyenneService->validateUeOption($ue->matieres()->first(),$historique);

            $ue_option->delete();   
            
        }


        foreach ($ue->matieres as $matiere) {
            $MoyenneService->updateMoyenne(
                $matiere,
                $historique
            );
        }

        return 'success';
    }

}
