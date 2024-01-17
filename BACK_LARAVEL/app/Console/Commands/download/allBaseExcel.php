<?php

namespace App\Console\Commands\download;

use App\Models\Parcour;
use App\Models\Semestre;
use Illuminate\Http\Request;
use App\Jobs\AllBaseExcelJob;
use Illuminate\Console\Command;

class allBaseExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:all-base-excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'download all excel data in same folder for each parcours';

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

        $request=new Request;

        $parcours=Parcour::all();
        $semestres=Semestre::all();

        foreach($parcours as $key=>$parcour){

            foreach ($semestres as  $semestre) {

                if($semestre->id==1)
                    continue;

                $folder=$this->createFolder($parcour,$semestre);

                $request->merge([
                    'is_validation' => 0,
                    'parcour_id'    => $parcour->id,
                    'semestre_id'   => $semestre->id,
                    'save'          => 1,
                    'folder'        => $folder
                ]);
        
                $this->exportResult($request);
                

            }

        }


        return 0;
    }


    private function createFolder(Parcour $parcour,Semestre $semestre){

        $semestre=$semestre->semestre;
        $parcour=$parcour->abreviation;

        return "/base-excel-facscience-2023/$parcour/$semestre";

    }


    private function exportResult(Request $request){
        
        AllBaseExcelJob::dispatch($request->semestre_id,$request->parcour_id,$request->is_validation,$request->folder);


    }

}
