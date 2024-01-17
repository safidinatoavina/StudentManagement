<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class exportTemplateAddNote implements FromCollection, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $data;
    public $head;

    public function __construct($data,$head){
        $this->data=$data;
        $this->head=$head;
    }

    public function collection()
    {
        $data=[
            ['','','',''],
            ['N°INSCRIPTION','NOM','PRENOM(S)','STATUT','NOTE/20']
        ];
        


        $etudiant=$this->data->map(function($historique){
            return [
                $historique->numeroInscription,
                $historique->etudiant->personne->nom,
                $historique->etudiant->personne->prenom,
                $historique->status->abreviation,
                ''
            ];
        })->toArray();

        return collect(array_merge($data,$etudiant));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:P1');
                $event->sheet->setCellValue('A1', 'Parcour: '.$this->head['parcour'].', Matiere: '.$this->head['matiere']);
            },
        ];
    }
}
