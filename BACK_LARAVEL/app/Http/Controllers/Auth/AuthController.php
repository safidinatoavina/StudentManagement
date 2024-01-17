<?php

namespace App\Http\Controllers\Auth;

use Captcha;
use App\Models\User;
use App\Models\Personne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate(['captcha' => 'required|captcha_api:'. request('key') . ',flat']);

        $user=User::with(['personne','personne.photo','roles','parcours','parcour_responsables'])
            ->where('login',$request->login)
            ->first();

   

        if($user && Hash::check($request->password, $user->password))
        {
            $token = $user->createToken("auth_token");

            $response=[
                'user'=>$user->toArray(),
                'auth_token'=>$token->plainTextToken
            ];

            return $response;
        }
        else
        {

            //ici, le mail n'est pas dans la base ou le password est incorrect
            return \response([
                'message'=>'Login ou mot de passe incorrect '
            ],401);
             
        }
    }

    public function capchat()
    {
        return Captcha::src('flat');

    }

    public function register(Request $request)
    {

        $this->authorize('create', User::class);

        $validated=$request->validate([
            'login'          =>'required|unique:users',
            'password'       =>[
                'required',
                Password::min(8)
                  ->mixedCase()
                  ->numbers()
            ],
            'nom'            =>'required',
            'prenom'         => 'required',
            'cin'            => 'required',
            'date_naissance' => 'required'
        ]);

        $personne=Personne::create([
            'nom'               => $validated['nom'],
            'prenom'            => $validated['prenom'],
            'cin'               => $validated['cin'],
            'date_naissance'    => $validated['date_naissance']
        ]);

        $user=User::create([
            'login'         =>$validated['login'],
            'password'      =>Hash::make($validated['password']),
            'personne_id'   => $personne->id
        ]);

        $result['user']=$user->toArray();
        $result['user']["personne"]=$personne->toArray();
        $result['auth_token']=$user->createToken("auth_token")->plainTextToken;


        return $result;

    }

    public function profile()
    {
        $auth=User::with(['personne','personne.photo','roles','parcours','parcour_responsables'])
            ->where('id',auth()
            ->user()->id)
            ->first();
        return $auth;
    }

    public function logout()
    {

        $user_id=auth()->user()->id;
        auth()->user()->tokens()->delete();

        DB::table('personal_access_tokens')->where('tokenable_id',$user_id)->delete();

        return ['message' => 'déconnexion avec succès '];
    }
}

