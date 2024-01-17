<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function personne()
    {
        return $this->belongsTo(Personne::class);

    }

    public function parcours()
    {
        return $this->belongsTo(Parcour::class);
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }

    

}
