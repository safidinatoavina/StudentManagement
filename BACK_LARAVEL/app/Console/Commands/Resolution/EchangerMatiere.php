<?php

namespace App\Console\Commands\Resolution;

use App\Models\Matiere;
use Illuminate\Console\Command;
use App\Service\Moyenne\MoyenneService;

class EchangerMatiere extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'echanger:matiere';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'echange valeur';

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

        // ----------  update --------------

        $from=Matiere::find(769);
        $to=Matiere::find(1193);

        $this->echange($from,$to);
        //----------------------------------
        $this->resolveMoyenne($to);

        return 0;
    }


    public function echange(Matiere $from,Matiere $to){


        echo("Echange des notes ...\n");

        foreach ($from->notes()->where('session_id',2)->get() as $key => $note) {

            $to->notes()
                ->where('historique_id',$note->historique_id)
                ->where('semestre_id',$note->semestre_id)
                ->where('session_id',2)
                ->update(['valeur'=>$note->valeur,'is_set'=>$note->is_set]);
        }

    }

    public function resolveMoyenne(Matiere $matiere){


        echo("resolution moyenne ...\n");


        $historiques=$matiere->parcour->historiques;
        $MoyenneService=new MoyenneService;

        foreach ($historiques as $key => $historique) {
            $MoyenneService->updateMoyenne($matiere,$historique);
        }


    }

}
