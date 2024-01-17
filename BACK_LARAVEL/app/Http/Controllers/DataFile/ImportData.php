<?php

namespace App\Http\Controllers\DataFile;

use Excel;
use App\Models\TP;
use App\Models\Ue;
use App\Models\Matiere;
use App\Imports\importUE;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use App\Imports\importMatiere;
use App\Imports\EtudiantImport;
use App\Imports\importNoteByProf;
use App\Imports\importNoteTPByProf;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Etudiant\EtudiantController;

trait ImportData{


    public function importUser(Request $request){

        $request->validate([
            'file'=>'required|file'
        ]);

        Excel::import(new UserImport, $request->file);

        $adminController=new AdminController;

        return $adminController->admins();
    }

    public function importEtudiant(Request $request){

        $request->validate([
            'file'=>'required|file'
        ]);

        Excel::import(new EtudiantImport, $request->file);

        $EtudiantController=new EtudiantController;

        return $EtudiantController->etudiants();
        
    }
    
    public function importUE(Request $request)
    {
        $this->autorize_data();

        $request->validate([
            'file'=>'required|file'
        ]);

        Excel::import(new importUE, $request->file);

        return Ue::with('parcour')->get()->toArray();

    }

    public function importMatiere(Request $request)
    {
        $this->autorize_data();

        $request->validate([
            'file'=>'required|file'
        ]);

        Excel::import(new importMatiere, $request->file);

        return Matiere::with(['professeur.personne','ue','parcour','semestre'])->get()->toArray();

    }

    public function importNote(Request $request,Matiere $matiere){

        if(
            auth()->user()->matieres()->where('matieres.id',$matiere->id)->exists()
            ||
            auth()->user()->roles()->where('roles.id',7)->exists()
          ){
              Excel::import(new importNoteByProf($matiere), $request->file);
      
              $noteController=new NoteController;
      
              return $noteController->matiereNotes($matiere);
            }
            
            abort(400,"Cette action n'est pas autorisée");

    }


    public function importNoteTP(Request $request,TP $tp){

        if(
            auth()->user()->tps()->where('t_p_s.id',$tp->id)->exists()
            ||
            auth()->user()->roles()->where('roles.id',7)->exists()
        ){
            Excel::import(new importNoteTPByProf($tp), $request->file);
    
            $noteController=new NoteController;
    
            return $noteController->matiereNotesTP($tp);;
        }
        
        abort(400,"Cette action n'est pas autorisée");

    }

}
