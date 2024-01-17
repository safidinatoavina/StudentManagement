<?php

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 *  trait pour la gestion de fichier
 */

trait FileService{

    public function saveFile(Request $request,$input,$dossier='images')
    {
        if($request->hasFile($input)){
            if ($request->file($input)->isValid()) {
               $path = $request->photo->store('public/'.$dossier);
               return asset("storage".strstr($path,'/'.$dossier));
            }
            else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function deleteFile($url){

        $storage_path = strstr($url, "storage");
        $storage_path = 'public'.substr($storage_path, 7);
        
        return Storage::delete($storage_path);

    }

}
