<?php

// namespace App\Http\Controllers\Admin;

// use App\Models\Matiere;
// use Illuminate\Http\Request;
// use App\Service\ResponseService;
// use App\Http\Controllers\Controller;

// class MatiereController extends Controller
// {
//     public function index()
//     {
//         return Matiere::all();
//     }

//     public function create(Request $request)
//     {
//         $data=[
//             'parcour_id'=>$request->parcour,
//             'ue_id'     =>$request->ue,
//             'matiere'   =>$request->matiere
//         ];

//         return Matiere::create($data);
//     }

//     public function show(Matiere $matiere)
//     {
//         return $matiere;
//     }

//     public function update(Request $request,Matiere $matiere)
//     {
//         $data=[
//             'parcour_id'=>$request->parcour,
//             'ue_id'     =>$request->ue,
//             'matiere'   =>$request->matiere
//         ];

//         $matiere->update($data);

//         return ResponseService::update(true);

//     }

//     public function destroy(Matiere $matiere)
//     {
//         $matiere->delete();

//         return ResponseService::delete(true);
//     }

//     //-------------service------------------


//     public function etudiants(Matiere $matiere)
//     {
//         return $matiere->parcour
//             ->historiques
//             ->with('etudiant');
//     }
// }
