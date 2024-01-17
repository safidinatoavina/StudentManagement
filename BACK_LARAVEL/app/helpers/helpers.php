<?php 

function annee(){

	$default_data=new \App\Service\DefaultData;
	return $default_data->getAnnee();

}

function enCours(){
	
	$default_data=new \App\Service\DefaultData;
	return $default_data->getEnCours();

}


function validerHistorique($etudiant_id,$parcour_id,$anne_id){
	$histo_validate=\App\Models\Historique::where('etudiant_id',$etudiant_id)
	->where('parcour_id',$parcour_id)
	->where('annee_universitaire_id',$anne_id)
	->first();

	if($histo_validate){
		abort(400,'cette étudiant a été déjà inscrit dans cette parcour pour cette année');
	}
}

