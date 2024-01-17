<?php

use App\Http\Controllers\data_faculte\DataFaculteController;

Route::middleware('auth:sanctum')->controller(DataFaculteController::class)->prefix('data-faculte')->group(function(){

    Route::get('/all','all');
    Route::get('/mentions','mentions');
    Route::get('/grades','grades');
    Route::get('/get-parcour-jury/{jury}','getParcourJury');
    Route::get('/parcours','parcours');
    Route::post('/gestion-active','GestionActive')->middleware('admin');
    Route::get('/get-active','getActive');
    Route::post('/add-mention','addMention')->middleware('admin');
    Route::post('/update-mention/{mention}','updateMention')->middleware('admin');
    Route::post('/add-parcour','addParcour')->middleware('admin');
    Route::post('/update-parcour/{parcour}','updateParcour')->middleware('admin');
    Route::post('/add-grade','addGrade')->middleware('admin');
    Route::post('/add-annee','addAnnee')->middleware('admin');
    Route::post('/update-statut-annee/{annee}','updateStatutAnnee')->middleware('admin');
    Route::post('/add-ue','addUe')->middleware('admin');
    Route::post('/add-tp','addTP')->middleware('admin');
    Route::post('/set-nombre-ue-obi-parmi-option','setNombreUesObli')->middleware('admin-responsable');
    Route::get('/get-nombre-ue-obi-parmi-option','getNombreUesObli')->middleware('admin-responsable');
    Route::post('/update-tp/{tp}','updateTP')->middleware('admin');
    Route::post('/add-matiere','addMatiere')->middleware('admin');
    Route::put('/update-matiere/{matiere}','editMatiere')->middleware('admin');
    Route::put('/update-ue/{ue}','editUE')->middleware('admin');
    Route::put('/update-matiere-status/{matiere}','updateStatusMatiere')->middleware('admin');
    Route::delete('/delete-parcour/{parcour}','deleteParcour')->middleware('admin');
    Route::delete('/delete-grade/{grade}','deleteGrade')->middleware('admin');
    Route::delete('/delete-mention/{mention}','deleteMention')->middleware('admin');
    Route::delete('/delete-annee/{annee}','deleteAnnee')->middleware('admin');
    Route::delete('/delete-ue/{ue}','deleteUe')->middleware('admin');
    Route::delete('/delete-matiere/{matiere}','deleteMatiere')->middleware('admin');
    Route::delete('/delete-tp/{tp}','deleteTP')->middleware('admin');


});


