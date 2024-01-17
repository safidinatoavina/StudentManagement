<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class);

    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function photo(){
        return $this->hasOne(PersonnePhoto::class);
    }

}
