<?php

namespace App\Http\Controllers\ckeditor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CkeditorController extends Controller
{
        //add image in ckeditor


        public function addImage(Request $request)
        {
    
            /**
             *  make a validation for the upload given in data request
             */

            $validator=Validator::make($request->all(), [
                'upload'=>'required|image'
            ]);
    
            if ($request->file('upload')->isValid() && $validator->passes()) {
    
                /**
                 *  now, image is valide and store in storage/app/public/images/page
                 */
    
                $path = $request->upload->store('public/ckeditor-images');
    
                //get the url of the image success stored
                $url_image=asset("storage".substr($path,6));
    
                $array_path=explode('/',$path);
    
    
                return [
                    "uploaded"=>1,
                    "fileName"=> end($array_path),
                    "url"     => $url_image
                ];
            }
            else
            {
                /**
                 *  an error is occcured here
                 */
                return [
                    "uploaded"=> 0,
                    "error"=> ["message"=> "image invalide"]
                ];
            }
        }
}
