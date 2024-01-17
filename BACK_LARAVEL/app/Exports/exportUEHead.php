<?php

namespace App\Exports;

use App\Models\Parcour;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class exportUEHead implements FromCollection,
ShouldAutoSize, WithColumnFormatting, WithEvents,WithColumnWidths
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
        return new Collection([$this->data]);
    }

    public function columnWidths(): array
    {
        return [
            'D' => 30,            
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '0', // Format de colonne par défaut pour le texte
            'C' => '0', // Format de colonne par défaut pour le texte
            'D' => '@',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                // Définir les options de la liste déroulante
                $options = Parcour::all()->map(function($item){
                    return $item->abreviation;
                })->toArray();

                // Ajouter la validation de données à la cellule B2
                //$sheet->setCellValue('C1', $options[0]);
                for ($line = 2; $line <= env("MAX_LINE_VALIDATION_EXCEL", 3000); $line++) {

                    $validation = $sheet->getCell("D$line")->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Erreur de validation des données');
                    $validation->setError('La valeur saisie n\'est pas valide.');
                    $validation->setPromptTitle('Liste déroulante');
                    $validation->setPrompt('Sélectionnez une option');
                    $validation->setFormula1(sprintf('"%s"', implode(',', $options)));

                }
            },
        ];
    }
}
