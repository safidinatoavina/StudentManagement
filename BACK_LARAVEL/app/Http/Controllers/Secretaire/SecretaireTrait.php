<?php

namespace App\Http\Controllers\Secretaire;

use App\Models\Historique;

trait SecretaireTrait{

	public function getListNote(Historique $historique){
		
		$notes=$historique->moyenne_matieres()->with(['matiere','matiere.semestre'])->get();
		$total_ue_valide=$historique->moyenne_ues()->where('valeur','>=',10)->count();

		$total_note_with_coefficient=0;
		$total_coefficient=0;

		$notes->each(function($item) use (&$total_note_with_coefficient,&$total_coefficient) {
			
			$total_note_with_coefficient+=($item->valeur * $item->matiere->coefficient);
			$total_coefficient+=$item->matiere->coefficient;

		});

		$moyenne=(float)($total_note_with_coefficient/$total_coefficient);


		return [
			'notes'            		=>$notes,
			'annee'					=>$historique->annee_universitaire->valeur,
			'numeroInscription'		=>$historique->numeroInscription,
			'nom'			   		=>$historique->etudiant->personne->nom,
			'prenom'		   		=>$historique->etudiant->personne->prenom,
			'moyenne'		   		=>$moyenne,
			'total_ue_valide'  		=>$total_ue_valide
		];

	}

}

