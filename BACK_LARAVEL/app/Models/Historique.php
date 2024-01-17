<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function parcour(){
        return $this->belongsTo(Parcour::class);
    }

    public function notes(){
        return $this->hasMany(Note::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function annee_universitaire()
    {
        return $this->belongsTo(AnneeUniversitaire::class);
    }

    public function moyenne_matieres(){
        return $this->hasMany(MoyenneMatiere::class);
    }

    public function moyenne_ues(){
        return $this->hasMany(MoyenneUe::class);
    }

    public function moyenne_semestres(){
        return $this->hasMany(MoyenneSemestre::class);
    }

    public function moyenne_annee(){
        return $this->hasOne(MoyenneAnnee::class);
    }

    public function ue_options(){
        return $this->hasMany(UeOption::class);
    }

    public function note_tps(){
        return $this->hasMany(NoteTp::class);
    }

    
}
