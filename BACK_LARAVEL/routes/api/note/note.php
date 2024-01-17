<?php
use App\Http\Controllers\Admin\NoteController;


Route::middleware('auth:sanctum')->controller(NoteController::class)->group(function(){
    Route::get('/notes','index')->middleware('admin');
    Route::get('/note/{note}','show')->middleware('admin');
    Route::post('/save-note','store');
    Route::post('/save-note-tp','storeTP');
    Route::post('/vider-note','ViderNote')->middleware('admin');
    Route::post('/vider-note-tp','ViderNoteTp')->middleware('admin');
    Route::post('/update-note/{note}','update')->middleware('admin');
    //Route::delete('/supprimer-note/{note}','destroy')->middleware('admin');
    Route::get('/validation-ue-matiere/{matiere}','validationUeMatiere');
    Route::get('/matiere-notes/{matiere}','matiereNotes');
    Route::get('/matiere-notes-tp/{tp}','matiereNotesTP');
    Route::get('/session-notes/{session}','sessionNotes')->middleware('admin');
    
});

