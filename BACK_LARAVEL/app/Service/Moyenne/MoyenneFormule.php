<?php

namespace App\Service\Moyenne;

use App\Models\Matiere;
use App\Models\Historique;

/**
 *  par defaut tous les etudiant a deja des note des matieres dans son parcour
 *  ce qui reste a faire est de mettre a jour son moyenne selon le matiere qu'il a fait son examen
 */

interface MoyenneFormule{


    /**
     *  methode pour resoudre le moyenne de matiere dans la table moyenne_matieres
     * @param historique : historique de l'etudiant
     * @param matiere : matiere que l'on veut mettre a jour sa moyenne
     */

     public function updateMoyenneMatiere(Matiere $matiere,Historique $historique);

     /**
      *  methode pour resoudre le moyenne de son ues dans la table moyenne_ues
      * @param historique : historique de l'etudiant
      * @param matiere : matiere que l'on veut mettre a jour sa moyenne
      */

     public function UpdateMoyenneUes(Matiere $matiere,Historique $historique);

     /**
      *  methode pour calculer la moyenne par semestre
      * @param historique : historique de l'etudiant
      * @param semestre_id : id du semestre que l'on veut calculer , valeurs possible(1,2) voir table semestres
      */

      public function updateMoyenneSemestre(Historique $historique,int $semestre_id=1);

      /**
       *  methode pour calculer la moyenne annee
       * @param historique : historique de l'etudiant
       */

     public function updateMoyenneAnnee(Historique $historique);


     /**
      *  methode pour factoriser tous les formule
      */

      public function updateMoyenne(Matiere $matiere,Historique $historique,int $semestre_id=1);

}

