<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NombreUeOptionObligatoir extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function parcour(){
        return $this->belongsTo(Parcour::class);
    }

    public function semestre(){
        return $this->belongsTo(Semestre::class);
    }

}
