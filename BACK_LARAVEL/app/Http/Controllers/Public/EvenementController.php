<?php

namespace App\Http\Controllers\Public;

use App\Models\Evenement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EvenementController extends Controller
{
    public function index()
    {
        return Evenement::orderBy('id','desc')->get();
    }

    public function show(Evenement $evenement)
    {
        return $evenement;
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'   => 'required',
            'contenu' => 'required'
        ]);

        $evenement=Evenement::create($request->all());

        return Evenement::find($evenement->id);

    }

    public function update(Request $request,Evenement $evenement)
    {
        $request->validate([
            'titre'   => 'required',
            'contenu' => 'required'
        ]);

        $evenement->update($request->all());

        return $evenement;
        
    }

    public function updateStatus(Evenement $evenement,Request $request)
    {

        $request->validate(['is_active'=>'required|min:0|max:1']);
        $evenement->update(['is_active'=>$request->is_active]);

        return ['success'=>true];

    }

    public function destroy(Evenement $evenement)
    {
        $evenement->delete();
        return ["success"=>true];
    }


    public function getActiveEvenement()
    {
        $evenements=Evenement::whereIsActive(1)
            ->where('updated_at','>=',now()->submonth(10))
            ->get();

        return $evenements;
    }

}
