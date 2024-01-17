<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ue extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function matieres(){
        return $this->hasMany(Matiere::class);
    }

    public function ue_publics(){
        return $this->hasMany(UePublic::class);
    }

    public function parcour(){
        return $this->belongsTo(Parcour::class);
    }

    public function moyenne_ues(){
        return $this->hasMany(MoyenneUe::class);
    }

    public function semestre(){
        return $this->belongsTo(Semestre::class);
    }

}
