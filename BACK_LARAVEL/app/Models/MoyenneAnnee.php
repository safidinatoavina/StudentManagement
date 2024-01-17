<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoyenneAnnee extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function historique(){
        return $this->belongsTo(Historique::class);
    }
}
