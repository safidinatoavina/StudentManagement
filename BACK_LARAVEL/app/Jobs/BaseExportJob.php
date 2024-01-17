<?php

namespace App\Jobs;

use Excel;
use App\Models\Parcour;
use App\Models\Semestre;
use Illuminate\Bus\Queueable;
use App\Exports\MoyenneUeExport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Service\Notification\NotificationService;


class BaseExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

     public $timeout = 7200; // Par exemple, 10 minutes de limite de temps

     public $semestre_id;
     public $parcour_id;
     public $is_validation;

    public function __construct(
        $semestre_id,$parcour_id,$is_validation,
        public NotificationService $NotificationService
        ){
        $this->semestre_id=$semestre_id;
        $this->parcour_id=$parcour_id;
        $this->is_validation=$is_validation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {



        $select_semestre=Semestre::find($this->semestre_id)??enCours()->semestre;

        $parcour=Parcour::with(['historiques'=>function($historique_query){
                        $historique_query->where('historiques.annee_universitaire_id',annee()->id);
                    },
                    'historiques.etudiant.personne',
                    'historiques.status',
                    'historiques.moyenne_semestres',
                    'matieres'=>function($query_matiere) use($select_semestre){
                        $query_matiere->where('semestre_id',$select_semestre->id);
                    },
                    'matieres.ue.moyenne_ues'=>function($moyenne_ue_query){
                        $moyenne_ue_query->whereHas('historique',function($historique_query){
                            $historique_query->where('historiques.annee_universitaire_id',annee()->id);
                        });
                    },
                    'matieres.notes'=>function($note_query) use($select_semestre) {
                        $note_query->whereHas('historique',function($historique_query) use($select_semestre) {
                            $historique_query->where('historiques.annee_universitaire_id',annee()->id)
                                             ->where('semestre_id',$select_semestre->id);
                        });
                    }
                    ,
                    'matieres.moyenne_matieres'=>function($moyenne_matiere_query){
                        $moyenne_matiere_query->whereHas('historique',function($historique_query){
                            $historique_query->where('historiques.annee_universitaire_id',annee()->id);
                        });
                    },
                    'matieres.moyenne_matieres.historique.etudiant.personne',
                    'matieres.tp.note_tps'=>function($note_tp_query){
                        $note_tp_query->whereHas('historique',function($historique_query){
                            $historique_query->where('historiques.annee_universitaire_id',annee()->id);
                        });
                    }
                    ])
                    ->findOrFail($this->parcour_id);

        $matieres=$parcour->matieres->groupBy(function($item){
            return $item->ue->ue;
        });


        $historiquesChunks=$parcour->historiques->sortBy([
                ['etudiant.personne.nom','asc'],
                ['etudiant.personne.prenom','asc']
            ])->chunk(300);

        $data['is_validation']=$this->is_validation;
        $data['data']=$matieres;
        $data['annee']=annee()->valeur;
        $data['parcour']=$parcour;
        $data['semestre']=$select_semestre;

        $pages=[
            'total'=>$historiquesChunks->count()
        ];




        foreach ($historiquesChunks as $key => $historiques) {
            $abref_parcour=$parcour->abreviation;
            $index=$key+1;
            $name="base-result-$abref_parcour-($index).xlsx";
            $data['historiques']=$historiques;
            
            $page=$index;
            $total=$pages['total'];
            $parcour_name=$data['parcour']->parcour;
            \Excel::store(new MoyenneUeExport($data),"public/download-excel/".$name);
            $url=\asset("storage/download-excel/".$name);
            $this->NotificationService->create("Export excel ($page/$total)",'('.$parcour_name.') ,<a href="'.$url.'" target="__blank">télécharger</a>');
        }

    }
}

