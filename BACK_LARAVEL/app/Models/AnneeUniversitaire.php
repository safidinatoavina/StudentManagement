<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeUniversitaire extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }

    public function ue_publics(){
        return $this->hasMany(UePublic::class);
    }

    public function count_etudiants(){
        return $this->belongsTo(CountEtudiant::class);
    }

    public function critere_admis(){
        return $this->hasMany(CritereAdmis::class);
    }


}
