<?php

namespace App\Jobs;

use App\Models\Matiere;
use App\Models\Historique;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Service\Moyenne\MoyenneService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class calculMoyenneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

     public $historique;
     public $matiere;
     public $semestre_id;


    public function __construct(int $matiere,int $historique,$semestre_id)
    {
        $this->matiere=Matiere::find($matiere);
        $this->historique=Historique::find($historique);
        $this->semestre_id=$semestre_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $MoyenneService=new MoyenneService;

        $MoyenneService->calculateAll($this->matiere,$this->historique,$this->semestre_id);

    }
}
