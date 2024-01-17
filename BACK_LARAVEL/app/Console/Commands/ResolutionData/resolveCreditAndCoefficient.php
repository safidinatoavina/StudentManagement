<?php

namespace App\Console\Commands\ResolutionData;

use App\Models\Ue;
use App\Models\Matiere;
use Illuminate\Console\Command;

class resolveCreditAndCoefficient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resolve:credit-and-coefficient';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'resoudre le coefficient et le credit car il y a une moment ou ce dpnnee est faux';

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


        $this->resolveCoefficientUE();

        echo("\n");

        return 0;
    }


    public function resolveCoefficientUE()
    {

        echo("\nresolution coefficient UE\n");

        $ues=Ue::all();
        $count=$ues->count();
        foreach ($ues as $key => $ue) {

            $coefficient=$ue->credit/2;

            $ue->update(['coefficient'=>$coefficient]);
            $this->calcProgress($key,$count);

            $ue->moyenne_ues()->whereHas('historique',function($query){
                $query->where('annee_universitaire_id',annee()->id);
            })
            ->update(['default_coefficient'=>$coefficient,'default_credit'=>$ue->credit]);

        }
    }


    private function calcProgress($key,$count){
        $key=$key+1;
        $percent=round(((int)$key/($count))*100,0);
        $percent=intval($percent);
        $this->messageOneLine("progress --> ".$percent."%");
    }

    private function messageOneLine($message){

        $this->output->write("\r $message");
    }

}
