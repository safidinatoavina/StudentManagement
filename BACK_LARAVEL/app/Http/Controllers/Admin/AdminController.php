<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Personne;
use App\Service\FileService;
use Illuminate\Http\Request;
use App\Models\PersonnePhoto;
use App\Service\Cache\SystemeCache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class AdminController extends Controller
{

    use FileService;

    public function admins(){

        $cache=new SystemeCache;
        
        return $cache->liste_admins();

    }

    public function liste_admins(){
        return User::with(['roles','personne','personne.photo'])->get()->sortBy([['personne.nom','asc'],['personne.prenom','asc']]);
    }

    public function store(Request $request){

        $this->authorize('create', User::class);

        $request->validate([
            'nom'               =>   'required',
            'prenom'            =>   'nullable',
            'date_naissance'    =>   'required',
            'lieu_naissance'    =>   'required',
            'cin'               =>   'required|unique:personnes',
            'address'           =>   'required',
            'login'             =>   'required|min:5|unique:users',
            'password'       =>[
                'required',
                Password::min(8)
                  ->mixedCase()
                  ->numbers()
            ],            
            'roles'             =>   'required'

        ]);
        
        $personne=Personne::create([
            'nom'               => $request->nom,
            'prenom'            => $request->prenom,
            'date_naissance'    => $request->date_naissance,
            'lieu_naissance'    => $request->lieu_naissance,
            'cin'               => $request->cin,
            'address'           => $request->address,
        ]);

        $photo=$this->saveFile($request,'photo','images');



        if($personne){

            $user=User::create([
                'login'         => $request->login,
                'password'      => Hash::make($request->password),
                'personne_id'   => $personne->id 
            ]);

            if($photo){
                PersonnePhoto::create([
                    'url'           =>$photo,
                    'personne_id'   => $personne->id
                ]);
            }

            if($user){

                $user->roles()->sync($request->roles);

                return $this->admins();
                
            }else{
                abort(403,'creation user error');
            }

        }
        else{

            abort(403,'creation personne error');

        }




    }

    public function update(Request $request,User $user){

        $this->authorize('update', $user);


        $request->validate([
            'nom'               =>   'required',
            'date_naissance'    =>   'required',
            'lieu_naissance'    =>   'required',
            'cin'               =>   'required|unique:personnes,cin,'.$user->personne->id,
            'address'           =>   'required',
            'login'             =>   'required|min:5|unique:users,login,'.$user->id,
            'password'          =>  [
                'nullable',
                Password::min(8)
                  ->mixedCase()
                  ->numbers()
            ],   
            'roles'             =>   'required'

        ]);

        
        $personne=$user->personne->update([
            'nom'               => $request->nom,
            'prenom'            => $request->prenom,
            'date_naissance'    => $request->date_naissance,
            'lieu_naissance'    => $request->lieu_naissance,
            'cin'               => $request->cin,
            'address'           => $request->address,
        ]);

        $photo=$this->saveFile($request,'photo','images');


        if($personne){

            $user_data=[
                'login'         => $request->login,
                'password'      => Hash::make($request->password),
            ];

            if(empty($request->password)){
                unset($user_data['password']);
            }

            $user->update($user_data);

            if($photo){

                if($user->personne->photo){

                    $this->deleteFile($user->personne->photo->url);

                    $user->personne->photo->update([
                        'url'           =>$photo,
                    ]);

                }else{

                    PersonnePhoto::create([
                        'url'           =>$photo,
                        'personne_id'   => $user->personne->id
                    ]);
                }

            }

         

            if(auth()->user()->roles()->where('roles.id',1)->exists())
                $user->roles()->sync($request->roles);

            return $this->admins();
            

        }
        else{

            abort(500,'update personne error');

        }



    }

    public function deleteUser(User $user){

        //user est supprimer on delete par mysql
        $this->authorize('delete', $user);

        if($user->personne->photo){
            $this->deleteFile($user->personne->photo->url);
        }

        $user->personne->delete();

        return "success";
    }
}
