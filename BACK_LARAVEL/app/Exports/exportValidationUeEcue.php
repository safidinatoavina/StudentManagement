<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class exportValidationUeEcue implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $data;
    public $header;

    public function __construct($data,$header){
        $this->data=$data;
        $this->header=$header;
    }

    public function view(): View
    {
        return view('exports.validation-ue-ecue', [
            'data' => $this->data,
            'header' => $this->header
        ]);
    }

}
