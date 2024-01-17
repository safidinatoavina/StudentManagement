<?php

namespace App\Http\Controllers\statistique;

use App\Models\Parcour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatistiqueController extends Controller
{

  use StatNoteTrait;

  public function getUeMatieres()
  {
      $parcour=Parcour::with(['grade','ues','matieres'])->get()->sortBy([["grade.grade",'asc'],["grade.niveau",'asc']]);

      $labels=$parcour->map(function($item){
        return $item->parcour;
      });

      $ues=$parcour->map(function($item){
        return $item->ues->count();
      });

      $uesColor='#004b55';

      $matiers=$parcour->map(function($item){
        return $item->matieres->count();
      });

      $matiersColor='#f14826';


      return [
              'labels'   =>$labels,
              'datasets' =>[
                [
                  'label'           => "Nombre de ECUE chaque parcour",
                  'backgroundColor' => $matiersColor,
                  'data'            => $matiers
                ],
                [
                  'label'           => "Nombre d'UE chaque parcour",
                  'backgroundColor' => $uesColor,
                  'data'            => $ues
                ]
              ]
      ];

  }
}



