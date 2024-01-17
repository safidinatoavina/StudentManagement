<?php

use App\Http\Controllers\ckeditor\CkeditorController;

Route::controller(CkeditorController::class)->prefix('ckeditor')->group(function(){
    Route::post('/ckeditor-upload','addImage');
});

