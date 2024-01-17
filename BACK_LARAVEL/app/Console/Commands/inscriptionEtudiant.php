<?php

namespace App\Console\Commands;

use App\Models\Status;
use App\Models\Parcour;
use App\Models\Etudiant;
use App\Models\Personne;
use App\Models\Historique;
use App\Service\DataService;
use Illuminate\Console\Command;
use App\Models\AnneeUniversitaire;

class inscriptionEtudiant extends Command
{
    /**
     * ceci est utilisé uniquement en test pour la simulation des données
     *
     * @var string
     */
    protected $signature = 'inscription:etudiant {--status=p}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'simulation inscription etudiant';

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

        //echo($this->option('status')."\n");
        //echo($this->argument('status')."\n"); or arguments()
        //$this->ask('What is your name?');

        echo("\ninscription etudiant\n");
        DataService::createHistorique();

        return 0;
    }

}
