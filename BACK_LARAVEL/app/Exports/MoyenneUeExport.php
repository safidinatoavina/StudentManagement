<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MoyenneUeExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $data=[];

    public function __construct($data){
        $this->data=$data;
    }


    public function view(): View
    {
        return view('exports.moyenne-base', [
            'data' => $this->data
        ]);
    }
}
