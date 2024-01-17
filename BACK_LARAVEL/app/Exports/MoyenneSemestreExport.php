<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class MoyenneSemestreExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $data=[];

    public function __construct($data){
        $this->data=$data;
    }

    public function collection()
    {

        $export=[[
            'N°INSCRIPTION',
            'NOM',
            'PRENOM(S)',
            'MOYENNE',
            'TOTAL UE VALIDE',
            'TOTAL CREDIT OBTENUE'
        ]
        ];


        foreach ($this->data['items'] as $item) {

            $export[]=[

                
                $item['numeroInscription'],
                $item['nom'],
                $item['prenom'],
                $this->data['form']=='validation'?$item['validation']:$item['moyenne'],
                $item['total_ue_valide'],
                $item['total_credit']
            ];
        }


        return new Collection([$export]);
    }
}
