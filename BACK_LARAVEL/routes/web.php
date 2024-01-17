<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFile\DataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(env('FRONT_URL'))
        return redirect(env('FRONT_URL'));
    else
        return view('welcome');
});

Route::get('/test-abc',[DataController::class,'exportResultatBase']);

