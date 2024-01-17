<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Route;


$routes=[
    
    "auth"                            => "auth",

    "etudiant"                        => "etudiant",

    "note"                            => "note",

    "admin"                           => "admin",

    "role"                            => "role",

    "professeur"                      => "professeur",

    "data_faculte"                    => "data_faculte",

    "filter"                          => "filter",

    "pdf"                             => "pdf",

    "operation"                       => "operation",

    'data'                            => 'data',

    'reinscription'                   => 'reinscription',

    'secretaire'                      => 'secretaire',

    'public_etudiant'                 => 'public_etudiant',

    'ckeditor'                        => 'ckeditor',

    'evenement'                       => 'evenement',

    'responsable'                     => 'responsable',

    'verification'                    => 'verification',

    'statistique'                     => 'statistique',

    'notification'                     => 'notification'


];

foreach($routes as $dossier=>$fichier){

    
    require_once("api/".$dossier."/".$fichier.".php");
}



