<?php
use App\Http\Controllers\reinscription\ReinscriptionController;


Route::middleware('auth:sanctum')->controller(ReinscriptionController::class)
->prefix('reinscription')->group(function(){

    Route::post('/redoublant/{historique}','ReinscriptionRedoublante');
    Route::post('/passant/{historique}','ReinscriptionNormal');
    Route::get('/liste/admis/{parcour}','ListeAdmis');
    Route::get('/liste/redoublant/{parcour}','ListeRedoublants');
    Route::post('/results','getResults');
    Route::post('/handle','handleReinscription');


});

