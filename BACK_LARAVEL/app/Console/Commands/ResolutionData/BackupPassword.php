<?php

namespace App\Console\Commands\ResolutionData;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backup password';

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

        $users=DB::table('users')->get();

        foreach ($users as $user) {
            $password=$user->password;
            $user_id=$user->id;
            Storage::append('update.sql', "UPDATE users set password='$password' WHERE id=$user_id;");
        }


        return 0;
    }
}
