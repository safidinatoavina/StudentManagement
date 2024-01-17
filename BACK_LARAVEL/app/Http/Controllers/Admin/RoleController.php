<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    public function index(){

        return Role::All();
        
    }

    public function getRoules(){
        return auth()->user()->roles;
    }

    public function addRoles(Request $request){
        
        auth()->user()->roles()->attach([...$request->roles]);
        return [
            'status'=>'success'
        ];
    }

    public function deleteRoles(User $user,Role $role){
        
        $user->roles()->detach([$role->id]);

        return [
            'status'=>'success'
        ];
    }
}
