<?php

namespace App\Console\Commands\Verification;

use App\Models\Ue;
use Illuminate\Console\Command;

class CoefficientUe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resolution:coefficient-ue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Coefficient ue resolution';

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
        $this->resolve();
        return 0;
    }

    public function resolve(){
        
        $ues=Ue::all();

        foreach ($ues as $key => $ue) {

            $ue->update([
                'coefficient'=>$ue->credit
            ]);
            
            $ue->moyenne_ues()
            ->whereHas('historique',function($historique_query){
                $historique_query->where('annee_universitaire_id',annee()->id);
            })
            ->update([
                'default_coefficient'=>$ue->credit,
                'default_credit'=>$ue->credit
            ]);

        }

    }
}
