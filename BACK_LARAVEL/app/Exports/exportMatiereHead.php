<?php

namespace App\Exports;

use App\Models\Ue;
use App\Models\User;
use App\Models\Parcour;
use App\Models\Semestre;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class exportMatiereHead implements FromCollection,
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
            'A' => 25,
            'B' => 30,
            'C' => 30,        
            'D' => 25,    
        ];
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                // Définir les options de la liste déroulante
                $parcours = Parcour::orderBy('parcour','asc')->get()->map(function($item){
                    return $item->abreviation;
                })->toArray();

                // Définir les options de la liste déroulante
                $ues = Ue::with('parcour')->orderBy('ue','asc')->get()->map(function($item){
                    return $item->ue." (".$item->parcour->abreviation.")";
                })->toArray();

                $professeurs = User::with(['personne'])
                    ->has('personne')
                    ->whereHas('roles',function($query){
                        $query->where('roles.id',3);
                    })
                    ->get()
                    ->sortBy('personne.nom')
                    ->sortBy('personne.prenom')
                    ->map(function($item){
                    return $item->personne->nom." ".$item->personne->prenom;
                })->toArray();

                // Définir les options de la liste déroulante
                $semestres = Semestre::orderBy('id','desc')->get()->map(function($item){
                    return $item->semestre;
                })->toArray();

                $this->setListeDeroulant($sheet,$parcours,"B");
                $this->setListeDeroulant($sheet,$professeurs,"C");
                $this->setListeDeroulant($sheet,$ues,"D");
                $this->setListeDeroulant($sheet,$semestres,"E");
                
            },
        ];
    }

    private function setListeDeroulant(&$sheet,$options,$column){

        // Ajouter la validation de données à la cellule B2
        //$sheet->setCellValue('C1', $options[0]);
        for ($line = 2; $line <= env("MAX_LINE_VALIDATION_EXCEL", 3000); $line++) {

            $validation = $sheet->getCell($column."$line")->getDataValidation();
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

    }

}
