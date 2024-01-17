<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\User::truncate();
        \App\Models\Personne::truncate();
        \App\Models\Etudiant::truncate();
        \App\Models\User::factory(1)->create();
        \App\Models\PersonnePhoto::truncate();
        DB::table('role_user')->truncate();

        $admin=\App\Models\User::orderBy('id','asc')->first();
        $admin->update(['login'=>'admin@admin.com']);
        $admin->roles()->sync([1]);


        $this->call([
            RoleSeeder::class,
            //UeSeeder::class,
            //MentionSeeder::class,
            //ParcourSeeder::class,
            GradeSeeder::class,
            //AnneeUniversitaireSeeder::class,
            StatusSeeder::class,
            SessionSeeder::class,
            SemestreSeeder::class,
            //MatiereSeeder::class
            ]);

        Note::truncate();

        // for($i=0;$i<20;$i++)
        //     Artisan::call('inscription:etudiant');


        Schema::enableForeignKeyConstraints();
    }
}
