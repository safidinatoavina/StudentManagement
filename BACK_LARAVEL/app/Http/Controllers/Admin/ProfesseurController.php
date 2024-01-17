<?php

namespace App\Http\Controllers\Admin;

use App\Models\Matiere;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfesseurController extends Controller
{
    public function getMatieres()
    {
        return auth()->user()->matieres()
            ->whereHas('semestre',function($query){
                $query->where('semestres.id',enCours()->semestre->id);
            })
            ->with('parcour')->get();
    }

    public function getTP()
    {
        return auth()->user()->tps()->with(['matiere','matiere.parcour'])
            ->whereHas('matiere',function($matiere_query){
                $matiere_query->whereHas('semestre',function($query){
                    $query->where('semestres.id',enCours()->semestre->id);
                });
            })->get();
    }
}
