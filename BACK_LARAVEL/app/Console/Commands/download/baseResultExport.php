<?php

namespace App\Console\Commands\download;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Http\Controllers\DataFile\DataController;

class baseResultExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:base-result';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $DataController=new DataController;

        $request=new Request;

        $request->merge([
            'is_validation'=>1,
            'parcour_id'   =>36,
            'semestre_id'  =>2,
            'save'     =>1
        ]);

        $DataController->exportResultatBase($request);

        return 0;
    }
}
