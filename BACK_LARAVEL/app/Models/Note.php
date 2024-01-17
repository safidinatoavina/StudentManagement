<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function historique(){
        return $this->belongsTo(Historique::class);
    }

    public function semestre(){
        return $this->belongsTo(Semestre::class);
    }

    

}
