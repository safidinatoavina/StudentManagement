<?php

namespace App\Console\Commands\Resolution;

use App\Models\Note;
use App\Models\NoteTp;
use App\Models\Matiere;
use Illuminate\Console\Command;
use App\Service\Moyenne\MoyenneService;

class EchangerNoteTpEtMatiere extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resolution:echanger-note-tp-et-ecue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Echanger le note entre TP et ECUE  ( ID du ECUE passer en parametre)";

    public $MoyenneService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->MoyenneService=new MoyenneService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        echo("--------------Echanger le note entre TP et ECUE  ( ID du ECUE passer en parametre)----------\n\n");

        $matiere_id = $this->ask("Taper l'ID du matiere-->");

        $matiere=Matiere::with([
            'tp',
            'tp.note_tps'=>function($note_tp_query){

                    $note_tp_query->whereHas('historique',function($historique_query){
                        $historique_query->where('annee_universitaire_id',annee()->id);
                    });

                },
                
            'notes'=>function($note_query){

                $note_query->whereHas('historique',function($historique_query){
                    $historique_query->where('annee_universitaire_id',annee()->id);
                })->where('session_id',enCours()->session->id);

            }

        ])->find($matiere_id);

        // ----------  update --------------
        $this->echange($matiere);
        //----------------------------------
        $this->resolveMoyenne($matiere);

        return 0;
    }

    public function echange(Matiere $matiere){


        echo("Echange des notes ...\n");

        foreach ($matiere->notes as $key => $value) {

            $note=$value;
            $note_tp=$matiere->tp->note_tps()->where('historique_id',$value->historique_id);
            //update tp

            if($note_tp->count()>1)
                dd('erreur de note donnee');

            $note_tp=$note_tp->first();
            $note_tp_valeur=$note_tp->valeur;
            $note_tp_is_set=$note_tp->is_set;

            NoteTp::find($note_tp->id)->update(['valeur'=>(float)($note->valeur),'is_set'=>$note->is_set]);

            //update matiere


            Note::find($note->id)->update(['valeur'=>$note_tp_valeur,'is_set'=>$note_tp_is_set]);

        }

    }

    public function resolveMoyenne(Matiere $matiere){


        echo("resolution moyenne ...\n");


        $historiques=$matiere->parcour->historiques;

        foreach ($historiques as $key => $historique) {
            $this->MoyenneService->updateMoyenne($matiere,$historique);
        }


            

    }



}
