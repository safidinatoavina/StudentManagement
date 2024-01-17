<?php

namespace App\Http\Controllers\verification;

use App\Models\Ue;
use App\Models\Parcour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerificationDataController extends Controller
{
    public function getUeParcour(Request $request)
    {
        $parcour=Parcour::findOrFail($request->parcour_id);

        return Ue::with([
                'semestre','parcour','matieres',
                'matieres.notes'=>function($note_query){
                    $note_query->where('session_id',enCours()->session->id)
                        ->whereHas('historique',function($query_historique){
                            $query_historique->where('annee_universitaire_id',annee()->id);
                        });
                },
                'matieres.tp',
                'matieres.tp.note_tps'=>function($note_tp_query){
                    $note_tp_query->whereHas('historique',function($query_historique){
                        $query_historique->where('annee_universitaire_id',annee()->id);
                    });
                }
                ])
                ->whereHas('matieres',function($matiere_query){

                    $matiere_query->whereHas('notes',function($note_query){
                        $note_query->where('session_id',enCours()->session->id)
                            ->whereHas('historique',function($query_historique){
                                $query_historique->where('annee_universitaire_id',annee()->id);
                            });
                    });


                })
                ->whereHas('parcour',function($query) use($parcour){
                    $query->where('parcours.id',$parcour->id);
                })
                ->get();

    } 
}
