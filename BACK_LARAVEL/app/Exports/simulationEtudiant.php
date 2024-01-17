<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class simulationEtudiant implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $data;

    public function __construct($data)
    {
        $this->data=$data;
    }
    
    public function collection()
    {
        return collect($this->data);
    }
}
