<?php

namespace App\Http\Controllers\statistique;

use App\Models\Ue;
use App\Models\Matiere;
use App\Models\Parcour;
use Illuminate\Http\Request;


trait StatNoteTrait{

    public function getEtudiantHasNote(Request $request)
    {
        $parcour=Parcour::with(['grade','ues','matieres',
        'matieres.notes'=>function($query){
            $query->wherehas('session',function($session){
                $session->where('sessions.id',enCours()->session->id);
            })->whereHas('semestre',function($semestre){
                $semestre->where('semestres.id',enCours()->semestre->id);
            })->whereHas('historique',function($historique){
                $historique->where('annee_universitaire_id',annee()->id);
            });
        }])
        ->get()
        ->sortBy([["grade.grade",'asc'],["grade.niveau",'asc']]);

        $labels=$parcour->map(function($item){
            return $item->parcour;
          });

        $nombre_note_ajout=$parcour->map(function($item){
            $total_note_ajout_matiere=0;
            foreach ($item->matieres as $matiere) {
                $total_note_ajout_matiere+=$matiere->notes->where('is_set',1)->count();
            }
            return $total_note_ajout_matiere;
          });

        $nombre_note_total_necessaire=$parcour->map(function($item){
            $total_note_matiere=0;
            foreach ($item->matieres as $matiere) {

                $total_note_matiere_sans_critere=$matiere->notes->count();

                if(enCours()->session->id==2)//session normal
                {
                    $total_note_matiere+=$total_note_matiere_sans_critere;
                }else{ //rattrapage
                    
                    $matiere_has_moyenne_normal=Matiere::find($matiere->id)->notes()
                    ->whereHas('historique',function($historique){
                        $historique->where('annee_universitaire_id',annee()->id);
                    })
                    ->where('semestre_id',enCours()->semestre->id)
                    ->where('session_id',2)->where('valeur','>=',10)->count();

                    $total_note_matiere += $total_note_matiere_sans_critere-$matiere_has_moyenne_normal;

                }

            }
            return $total_note_matiere;
          });

          return [
            'labels'   =>$labels,
            'datasets' =>[
              [
                'label'           => "Nombre des notes ajouté chaque parcours",
                'backgroundColor' => 'blue',
                'data'            => $nombre_note_ajout
              ],
              [
                'label'           => "Nombre total de note nécessaire",
                'backgroundColor' => 'red',
                'data'            => $nombre_note_total_necessaire
              ]
            ]
    ];
    }


    public function getMatiereNoteStats()
    {

      $non_public_ues=Ue::with([
        'parcour',
        'semestre',
        'matieres',
        'matieres.tp',
        'matieres.tp.note_tps'=>function($query_note_tp){
          $query_note_tp->whereHas('historique',function($query_historique){
            $query_historique->where('historiques.annee_universitaire_id',annee()->id);
          });   
        },
        'matieres.notes'=>function($query_note){
          $query_note->whereHas('historique',function($query_historique){
            $query_historique->where('historiques.annee_universitaire_id',annee()->id);
          })->where('semestre_id',enCours()->semestre->id)
          ->where('session_id',enCours()->session->id);
        }
      ])
      ->whereDoesntHave('ue_publics',function($query_public){
        if(enCours()->session->id==2) //pour le session normal
          $query_public->where('annee_universitaire_id',annee()->id);
        else
          $query_public->where('annee_universitaire_id',annee()->id)->where('avec_ratrapage',1);
      })
      ->get()
      ->map(function($ue){

        $result=[
          'id'          =>$ue->id,
          'ue'          =>$ue->ue,
          'option'      =>$ue->option,
          'parcour_id'  => $ue->parcour_id,
          'parcour'     =>$ue->parcour->parcour,
          'semestre'    =>$ue->semestre->semestre,
          'matieres'    =>$ue->matieres->map(function($matiere){
            
            //note tp
            if($matiere->tp){
              $avec_note_tp=$matiere->tp->note_tps->where('is_set',1)->count();
              $tous_note_tp=$matiere->tp->note_tps->count();
            }else{
              $avec_note_tp=0;
              $tous_note_tp=0;
            }
            //note matiere
            $avec_note=$matiere->notes->where('is_set',1)->count();
            $tous_note=$matiere->notes->count();

            return [
              'id'                  =>$matiere->id,
              'matiere'             =>$matiere->matiere,
              'has_tp'              =>$matiere->tp?1:0,
              'tp'                  =>$matiere->tp?$matiere->tp->tp:0,
              'pourcent_matiere'    =>$tous_note?(round(($avec_note/$tous_note)*100,2)):0,
              'pourcent_tp'         =>$tous_note_tp?(round(($avec_note_tp/$tous_note_tp)*100,2)):0,
            ];

          })
        ];

        $collect_matiere=collect($result['matieres']);

        if(enCours()->session->id!=1){ // si session normal
          $numerateur=($collect_matiere->sum('pourcent_matiere') + $collect_matiere->where('has_tp','!=',0)->sum('pourcent_tp'));
          $denominateur=$collect_matiere->count()+$collect_matiere->where('has_tp','!=',0)->count();
        }
        else{
          $numerateur=$collect_matiere->sum('pourcent_matiere');
          $denominateur=$collect_matiere->count();
        }
        
        $result['pourcent']=round($denominateur?($numerateur/$denominateur):0,2);

        return $result;

      })
      ->sortByDesc('pourcent')
      ->values()
      ->all();



    $public_ues=Ue::with([
        'parcour',
        'semestre',
        'matieres',
        'matieres.tp',
        'matieres.tp.note_tps'=>function($query_note_tp){
          $query_note_tp->whereHas('historique',function($query_historique){
            $query_historique->where('historiques.annee_universitaire_id',annee()->id);
          });   
        },
        'matieres.notes'=>function($query_note){
          $query_note->whereHas('historique',function($query_historique){
            $query_historique->where('historiques.annee_universitaire_id',annee()->id);
          })->where('semestre_id',enCours()->semestre->id)
          ->where('session_id',enCours()->session->id);
        }
      ])
      ->whereHas('ue_publics',function($query_public){
        if(enCours()->session->id==2) //pour le semestre impaire
          $query_public->where('annee_universitaire_id',annee()->id);
        else
          $query_public->where('annee_universitaire_id',annee()->id)->where('avec_ratrapage',1);
      })
      ->get()
      ->map(function($ue){

        $result=[
          'id'          =>$ue->id,
          'ue'          =>$ue->ue,
          'parcour'     =>$ue->parcour->parcour,
          'option'      =>$ue->option,
          'parcour_id'  => $ue->parcour_id,
          'semestre'    =>$ue->semestre->semestre,
          'matieres'    =>$ue->matieres->map(function($matiere){
            
            //note tp
            if($matiere->tp){
              $avec_note_tp=$matiere->tp->note_tps->where('is_set',1)->count();
              $tous_note_tp=$matiere->tp->note_tps->count();
            }else{
              $avec_note_tp=0;
              $tous_note_tp=0;
            }
            //note matiere
            $avec_note=$matiere->notes->where('is_set',1)->count();
            $tous_note=$matiere->notes->count();

            return [
              'id'                  =>$matiere->id,
              'matiere'             =>$matiere->matiere,
              'pourcent_matiere'    =>$tous_note?(round(($avec_note/$tous_note)*100,2)):0,
              'pourcent_tp'         =>$tous_note_tp?(round(($avec_note_tp/$tous_note_tp)*100,2)):0,
            ];

          })
        ];

        $collect_matiere=collect($result['matieres']);

        $numerateur=($collect_matiere->sum('pourcent_matiere') + $collect_matiere->where('has_tp','!=',0)->sum('pourcent_tp'));
        $denominateur=$collect_matiere->count()+$collect_matiere->where('has_tp','!=',0)->count();
        $result['pourcent']=round($denominateur?($numerateur/$denominateur):0,2);

        
        return $result;

      })
      ->sortByDesc('pourcent')
      ->values()
      ->all();




       return [
        'non_public'=>$non_public_ues,
        'public'    =>$public_ues
       ];

    }



}
