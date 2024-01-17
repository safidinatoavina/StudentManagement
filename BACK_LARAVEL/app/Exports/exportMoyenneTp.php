<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class exportMoyenneTp implements FromView
{
    public $data;
    public $is_validation;
    public $parcour;

    public function __construct($data,$is_validation,$parcour){
        $this->data=$data;
        $this->is_validation=$is_validation;
        $this->parcour=$parcour;
    }

    public function view(): View
    {
        return view('exports.pdf-moyenne-tp', [
                'data' => $this->data,
                'is_validation' => $this->is_validation,
                'parcour' => $this->parcour
            ]);
    }

}
