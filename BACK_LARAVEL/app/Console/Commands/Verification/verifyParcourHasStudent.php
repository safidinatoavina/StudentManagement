<?php

namespace App\Console\Commands\verification;

use App\Models\Parcour;
use Illuminate\Console\Command;

class verifyParcourHasStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:parcour-avec-etudiant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commande pour verifier les parcour avec etudiant';

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

        echo("\nParcour qui n'a pas d'etudiant\n");
        
        $parcours=Parcour::all();

        foreach ($parcours as $key => $parcour) {
            if(!$parcour->historiques->count())
                echo("-Parcour:".$parcour->parcour."\n");
        }

        echo("\nFIN\n");

        return 0;
    }
}
