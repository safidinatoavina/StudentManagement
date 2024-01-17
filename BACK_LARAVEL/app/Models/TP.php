<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TP extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function matiere(){
        return $this->belongsTo(Matiere::class,'matiere_id');
    }

    public function professeur(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function note_tps(){
        return $this->hasMany(NoteTp::class,'tp_id');
    }

}
