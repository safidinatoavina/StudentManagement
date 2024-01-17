<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoyenneUe extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function historique(){
        return $this->belongsTo(Historique::class);
    }

    public function semestre(){
        return $this->belongsTo(Semestre::class);
    }

    public function ue(){
        return $this->belongsTo(Ue::class);
    }

}
