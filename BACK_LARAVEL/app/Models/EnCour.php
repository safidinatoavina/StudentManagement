<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class EnCour extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $appends=['annee'];

    public function semestre(){
        return $this->belongsTo(Semestre::class);
    }

    public function annee():Attribute
    {

        return new Attribute(
            get: fn () => annee(),
        );
    }

    public function session(){
        return $this->belongsTo(Session::class);
    }
}
