<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnePhoto extends Model
{
    use HasFactory;
    
    protected $guarded=[];

    public function personne(){
        return $this->belongsTo(Personne::class);
    }
}
