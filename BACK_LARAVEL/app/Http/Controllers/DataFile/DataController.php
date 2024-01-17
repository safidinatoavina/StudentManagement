<?php

namespace App\Http\Controllers\DataFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataController extends Controller
{

    private function autorize_data(){
        $is_ok = auth()->user()->roles()->where('roles.id',6)->exists() || auth()->user()->roles()->where('roles.id',1)->exists();
        if(!$is_ok)
            abort(400,"Action n'est pas autorisé");
    }

    use ImportData,ExportData;

}
