<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function professeur()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function parcour()
    {
        return $this->belongsTo(Parcour::class);
    }

    public function ue()
    {
        return $this->belongsTo(Ue::class);
    }

    public function moyenne_matieres(){
        return $this->hasMany(MoyenneMatiere::class);
    }

    public function semestre(){
        return $this->belongsTo(Semestre::class);
    }

    public function tp(){
        return $this->hasOne(TP::class,'matiere_id');
    }



}
