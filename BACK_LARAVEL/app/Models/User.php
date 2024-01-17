<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'password',
        'personne_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_verified_at' => 'datetime',
    ];

    public function personne()
    {
        return $this->belongsTo(Personne::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->using(RoleUser::class);
    }

    public function matieres(){
        return $this->hasMany(Matiere::class);
    }

    public function pdf(){
        return $this->hasOne(Pdf::class);
    }

    public function parcours(){
        //ceci est pour le jury uniquement
        return $this->belongsToMany(Parcour::class)->using(ParcourUser::class);
    }

    public function parcour_responsables(){
        return $this->belongsToMany(Parcour::class,'responsable_parcour','user_id','parcour_id')->using(ResponsableParcour::class);
    }

    public function tps(){
        return $this->hasMany(TP::class,'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}
