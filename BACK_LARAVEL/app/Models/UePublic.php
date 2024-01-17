<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UePublic extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function ue(){
        return $this->belongsTo(Ue::class);
    }

    public function parcour(){
        return $this->belongsTo(Parcour::class);
    }

    public function annee_universitaire(){
        return $this->belongsTo(AnneeUniversitaire::class);
    }
}
