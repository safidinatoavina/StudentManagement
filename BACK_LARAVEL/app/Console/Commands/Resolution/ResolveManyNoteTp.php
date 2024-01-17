<?php

namespace App\Console\Commands\Resolution;

use App\Models\TP;
use App\Models\NoteTp;
use App\Models\Matiere;
use Illuminate\Console\Command;
use App\Service\Moyenne\MoyenneService;

class ResolveManyNoteTp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resolve:note-tp-many';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'resoudre le note tp plusieur';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->listTpNoNote();
        $this->TpError();
        return 0;
    }

    private function TpError(){

        $tps=TP::all();

        foreach ($tps as $key => $tp) {

            if(!$tp->note_tps()->first())
                continue;

            $note_tps=$tp->note_tps()->where('historique_id',$tp->note_tps()->first()->historique_id)->count();

            if($note_tps>1)
                $this->deleteNoteInutil($tp);

        }


    }

    private function deleteNoteInutil(Tp $tp){
        
        $historiques=$tp->matiere->parcour->historiques()->where('annee_universitaire_id',annee()->id)->get();

        foreach ($historiques as $key => $historique) {

            $first=$tp->note_tps()->where('historique_id',$historique->id)->where('is_set',1)->first();
            
            if(!$first)
                $first=$tp->note_tps()->where('historique_id',$historique->id)->first();

            $tp->note_tps()->where('historique_id',$historique->id)->where('id','!=',$first->id)->delete();
        }

    }

    private function listTpNoNote(){

        $tps=TP::whereHas('matiere',function($query){

            $query->whereHas('parcour',function($query1){
                $query1->has('historiques');
            });

        })->doesntHave('note_tps')->get();

        $MoyenneService=new MoyenneService;

        foreach ($tps as $key => $tp) {
            echo("TP:".$tp->tp.",Parcour:".$tp->matiere->parcour->parcour."\n");

            $historiques=$tp->matiere->parcour->historiques()->where('annee_universitaire_id',annee()->id)->get();

            foreach ($historiques as $historique) {

                NoteTp::create([
                    'tp_id' =>$tp->id,
                    'historique_id' => $historique->id
                ]);

                $MoyenneService->updateMoyenne($tp->matiere,$historique);
            }
        }
    }

}
