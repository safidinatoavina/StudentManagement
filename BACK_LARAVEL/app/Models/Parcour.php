<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcour extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function ue_publics(){
        return $this->hasMany(UePublic::class);
    }

    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }

    public function ues(){
        return $this->hasMany(Ue::class);
    }

    public function mention()
    {
        return $this->belongsTo(Mention::class);
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function jury(){
        return $this->belongsToMany(User::class)->using(ParcourUser::class);
    }

    public function critere_admis(){
        return $this->hasMany(CritereAdmis::class);
    }

    public function has_final_result(){
        return PublicFinal::where('parcour_id',$this->id)->where('annee_universitaire_id',annee()->id)->first();
    }

    public function critere_validations()
    {
        return $this->hasMany(CritereValidation::class);
    }

    public function user_responsables(){
        return $this->belongsToMany(User::class,'responsable_parcour','parcour_id','user_id')->using(ResponsableParcour::class);
    }

    public function nombre_ue_option_obligatoires()
    {
        return $this->hasMany(NombreUeOptionObligatoir::class,'parcour_id');
    }

}
