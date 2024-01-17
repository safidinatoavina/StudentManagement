<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Personne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\Admin\AdminController;

class UserImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {

        foreach ($rows as $key => $user) {
            if($key==0)
                continue;

            if(empty($user[0])&&empty($user[1])&&empty($user[2])&&empty($user[3]))
                continue;

            $line=$key+1;

            $cin_duplicate=$rows->where('cin',trim($user[5]))->count();
            
            $cin=Personne::where('cin',trim($user[5]))->first();

            $login=User::where('login',trim($user[7]))->first();
            
            $role = DB::table('roles')->where('type',trim($user[6]))->get()->first();

            if(!$role)
                abort(400,"Role n'existe pas sur le plateforme sur line $line");

            if($cin)
                abort(400,"CIN déja pris line $line");

            if($cin_duplicate>1)
                abort(400,"Il y a meme cin que line $line dans le fichier à importer");
            
            if($login)
                abort(400,"login déjà pris line $line");
            
            if(strlen($user[7])<5)
                abort(400,"login a au moins 5 caractère line $line");

            if((!$this->validePassword($user[8])))
                abort(400,"mot de passe doivent au moins 8 caractère,au moins une chiffre et une lettre minuscule et une lettre majuscule, line $line");

        }

        //traitement

        $this->handle($rows);
        
    }

    public function validePassword($chaine) {
        if (strlen($chaine) < 8) {
            return false;
        }
        if (!preg_match('/[a-z]/', $chaine)) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $chaine)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $chaine)) {
            return false;
        }
        return true;
    }

    private function handle($rows)
    {

        $controller=new AdminController;

        foreach ($rows as $key => $user) {
            if($key==0)
                continue;

            if(empty($user[0])&&empty($user[1])&&empty($user[2])&&empty($user[3]))
                continue;

            $roles=DB::table('roles')->where('type',trim($user[6]))->get()->first();

            if($roles)
                $roles=[$roles->id];
            else
                $roles=[4];

            $request=request();
            $request->merge([
                'nom'=>$user[0],
                'prenom'=>$user[1],
                'date_naissance'=>implode('-',array_reverse(explode('/',$user[2]))),
                'lieu_naissance'=>$user[3],
                'address'       =>$user[4],
                'cin'           =>$user[5],
                'roles'         =>$roles,
                'login'         =>trim($user[7]),
                'password'      =>trim($user[8])
            ]);

            $controller->store($request);
        }

    }
}
