<?php 

namespace App\Http\Controllers\Admin\Operation;

use App\Models\Parcour;
use App\Models\PublicFinal;
use App\Models\SemestrePublic;

/**
 * trait pour l'operation
 */
trait OperationTrait
{
    public function getPublicationSemestre(Parcour $parcour)
    {

        return SemestrePublic::where('parcour_id',$parcour->id)
                            ->where('annee_universitaire_id',annee()->id)
                            ->where('semestre_id',enCours()->semestre->id)
                            ->first()?1:0;
    }


    public function getPublicationFinal(Parcour $parcour)
    {

        return PublicFinal::where('annee_universitaire_id',annee()->id)
            ->where('parcour_id',$parcour->id)
            ->first()?1:0;
    }

}
