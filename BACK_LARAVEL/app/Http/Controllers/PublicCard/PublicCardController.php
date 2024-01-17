<?php

namespace App\Http\Controllers\PublicCard;

use App\Models\CardHome;
use App\Service\FileService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PublicCardController extends Controller
{
    use FileService;
    
    public function index(){
        return CardHome::where('is_active',1)->get();
    }

    public function store(Request $request){

        $request->validate([
            'image'=>'required|file',
            'mention' => 'required',
            'description'=> 'required|min:10'
        ]);

        $image_url=$this->saveFile($request,'image','card-home');

        $data=[
            'image'=>$image_url??'',
            'mention' => $request->mention,
            'description'=> $request->description
        ];

        CardHome::create($data);
        
    }
}
