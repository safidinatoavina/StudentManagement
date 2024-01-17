<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteTp extends Model
{
    use HasFactory;
    protected $guarded=[];
    
    public function historique(){
        return $this->belongsTo(Historique::class);
    }
    
    public function tp(){
        return $this->belongsTo(TP::class,'tp_id');
    }

}
