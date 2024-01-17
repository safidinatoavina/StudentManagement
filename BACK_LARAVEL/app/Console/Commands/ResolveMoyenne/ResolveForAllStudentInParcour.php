<?php

namespace App\Console\Commands\ResolveMoyenne;

use App\Models\Parcour;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResolveForAllStudentInParcour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moyenne-resolution:pour-tous-etudiant-parcours {parcour}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "resoudre la table moyenne_annees et moyenne_semestres pour tous les étudiant de meme parcours selon le critere";

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

        $parcourID = $this->argument('parcour');

        $parcour=Parcour::find($parcourID);

        if(!$parcour){
            abort(400,"parcour introuvable dans la resolution validation");
        }

        foreach ($parcour->historiques as $key => $historique) {

            $historiqueID=$historique->id;
            Artisan::call("moyenne-resolution:pour-un-etudiant $historiqueID");
            
        }

        return 0;
    }
}

