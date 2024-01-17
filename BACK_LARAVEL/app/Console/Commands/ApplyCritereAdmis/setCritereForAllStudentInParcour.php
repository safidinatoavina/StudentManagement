<?php

namespace App\Console\Commands\ApplyCritereAdmis;

use App\Models\Parcour;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class setCritereForAllStudentInParcour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-critere:for-all-student-in-parcour {parcour}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command pour appliquer le critere pour tous les etudinats du parcour';

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
        $parcour=Parcour::find($this->argument('parcour'));

        if(!$parcour){
            abort(400,"parcour introuvable pour la mis en place du critere d'admission");
        }

        foreach ($parcour->historiques as $key => $historique) {
            $historiqueID=$historique->id;
            Artisan::call("set-critere-admis:for-etudiant $historiqueID");
        }

        return 0;
    }
}
