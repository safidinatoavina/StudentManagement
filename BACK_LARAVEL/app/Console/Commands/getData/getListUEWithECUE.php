<?php

namespace App\Console\Commands\getData;

use App\Models\Parcour;
use Illuminate\Console\Command;
use App\Exports\exportUeWithEcueInfo;

class getListUEWithECUE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:liste-ue-with-ecue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command pour obtenir un list';

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

        $parcours=Parcour::with(['ues','ues.matieres','ues.matieres.tp'])->get();


        \Excel::store(new exportUeWithEcueInfo($parcours),"resulat-verification.xlsx");

        return 0;
    }
}
