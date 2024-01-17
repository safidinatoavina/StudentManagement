<?php
use App\Http\Controllers\Secretaire\SecretaireController;


Route::middleware('auth:sanctum')
->controller(SecretaireController::class)->prefix('secretaire')->group(function(){
    Route::post('filtre-etudiant','EtudiantFilter');
    Route::post('/releve/note/{historique}','ReleveNoteEtudiant');
    Route::get('/fiche-presence/{parcour}','FichePresence');
});

