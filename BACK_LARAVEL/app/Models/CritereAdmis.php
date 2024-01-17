<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CritereAdmis extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function parcour(){
        return $this->belongsTo(Parcour::class);
    }

    public function annee_universitaire()
    {
        return $this->belongsTo(AnneeUniversitaire::class);
    }

    
}
