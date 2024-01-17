<?php

use App\Http\Controllers\DataFile\DataController;

Route::middleware('auth:sanctum')->controller(DataController::class)->prefix('data')->group(function(){
    Route::post('/export/moyenne-ue','exportMoyenneUe');
    Route::post('/import/ue',"importUE");
    Route::post('/import/matiere',"importMatiere");
    Route::post('/import/import-note/{matiere}',"importNote");
    Route::post('/import/import-note-tp/{tp}',"importNoteTP");
    Route::get('export/ue-head/{type}',"getUEHead");
    Route::get('export/matiere-head/{type?}',"getMatiereHead");
    Route::post('export/download-note-tp/{type?}',"getNoteTp");
    Route::post('/export/resultat-base','exportResultatBase');
    Route::post('/export/passage','exportPassage');
    Route::get('/export/prof-head/{type}','getProfHead');
    Route::post('/import/user','importUser')->middleware('admin');
    Route::get('/export/etudiant-head/{type}','getEtudiantHead');
    Route::post('/export/moyenne-semestre','exportMoyenneSemestre');
    Route::post('/import/etudiant','importEtudiant')->middleware('admin');
    Route::post('/export/note-template/{type}','exportTemplateNote');
    Route::post('/export/note-tp-template/{type}','exportTemplateNoteTP');
    Route::get('/download-validation-ue-matiere/{matiere}','exportValidationUeEcue');
});
