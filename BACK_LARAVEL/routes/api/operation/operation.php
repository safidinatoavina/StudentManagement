<?php
use App\Http\Controllers\Admin\Operation\OperationController;


Route::middleware('auth:sanctum')->controller(OperationController::class)->prefix('operation')
->group(function(){
    Route::post('liste-validation-ue','ValidationUe')->middleware('admin-jury');
    Route::post('liste-validation-matiere','ValidationMatiere')->middleware('admin-jury');
    Route::post('liste-admis','ListeAdmis')->middleware('admin-jury');
    Route::get('fetch-admis/{parcour}','FetchAdmis')->middleware('admin-jury');
    Route::get('fetch-redoublant/{parcour}','FetchRedoublant')->middleware('admin-jury');
    Route::post('set-critere-validation','setCritereSemestre')->middleware('admin-jury');
    Route::get('get-critere-validation/{parcour}','getCritereValidations')->middleware('admin-jury');
    Route::post('liste-redoublants','ListeRedoublants')->middleware('admin-jury');
    Route::post('liste-validation-par-semestre','ValidationParSemestre')->middleware('admin-jury');
    Route::post('public-result/{ue}/{parcour}/{annee?}','setPublicUe');
    Route::post('public-result-rattrapage/{ue}/{parcour}/{annee?}','setPublicUeRattrapage');
    Route::post('public-final-result/{parcour}','setPublicFinalResult');
    Route::post('public-result-semestre/{parcour}','setPublicSemestreResult');
    Route::post('cancel-result-semestre/{parcour}','cancelPublicSemestreResult');
    Route::get('get-public-semestre/{parcour}','getPublicationSemestre');
    Route::get('get-public-final/{parcour}','getPublicationFinal');
    Route::post('cancel-result/{ue}/{parcour}/{annee?}','cancelResult');
    Route::post('cancel-result-rattrapage/{ue}/{parcour}/{annee?}','cancelResultRattrapage');
    Route::post('cancel-final-result/{parcour}','cancelFinalResult');
    Route::get('ues-jury/{parcour}','uesJury');
    Route::post('pdf-ue-note-or-validation-definitive','ImprimerPdfDefinitive');
    Route::post('pdf-ue-note-or-validation','ImprimerPdf');
    Route::get('liste-etudiant/{parcour}','ImprimerPdfListeEtudiant');
    Route::get('critere-admis/{parcour}','getCritereAdmis')->middleware('admin-jury');
});

