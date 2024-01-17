<?php

use App\Http\Controllers\Admin\FiltreEtudiantController;


Route::middleware('auth:sanctum')->controller(FiltreEtudiantController::class)
->prefix('filter')->group(function(){

    Route::post('etudiant-with-note','filterWithNote');
    Route::post('etudiant-filter','filterEtudiant');
    Route::post('jury-filter-etudiant','JuryFilterEtudiant');

});
