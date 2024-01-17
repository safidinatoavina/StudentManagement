<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class PassageANiveauExport implements FromCollection
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
        $data=[[
            'N°INSCRIPTION',
            'NOM',
            'PRENOM(S)',
            'UE OBTENUE',
            'CREDIT OBTENUE',
            'MOYENNE'
        ]];

        foreach ($this->data['items'] as $item) {
            $data[]=[
                $item['numeroInscription'],
                $item['etudiant']['personne']['nom'],
                $item['etudiant']['personne']['prenom'],
                $item['moyenne_annee']['total_ue_valide'],
                $item['moyenne_annee']['total_credit'],
                $item['moyenne_annee']['valeur']
            ];
        }

        return collect($data);
    }
}
