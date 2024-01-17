<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Service\Cache\SystemeCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Http\Controllers\Admin\AdminController;

class refreshCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

     public $SystemeCache;
     public $methode;

    public function __construct($methode)
    {
        $this->SystemeCache=new SystemeCache(true,false);
        $this->methode=$methode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->SystemeCache->{$this->methode}();
    }
}
