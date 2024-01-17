<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoyenneMatiere extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function historique(){
        return $this->belongsTo(Historique::class);
    }

    public function matiere(){
        return $this->belongsTo(Matiere::class);
    }

}
