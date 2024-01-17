<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    use HasFactory;
    
    protected $guarded=[];

    public function moyenne_ues(){
        return $this->hasMany(MoyenneUe::class);
    }

    public function moyenne_semestres(){
        return $this->hasMany(MoyenneSemestre::class);
    }

}
