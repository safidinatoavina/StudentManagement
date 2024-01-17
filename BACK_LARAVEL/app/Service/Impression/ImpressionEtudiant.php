<?php

namespace App\Service\Impression;

use PDF;

/*---with alias---*/
use App\Service\FileService;
use Illuminate\Http\Request;


class ImpressionEtudiant{

	use FileService;


	private function deleteLastFile(){

		if(!auth()->user()->pdf)
			return;

		$this->deleteFile(auth()->user()->pdf->link);
		auth()->user()->pdf()->delete();


	}


	public function GeneratePDF($data,$view){

		$pdf = PDF::loadView($view, [
	       'data' => $data
	    ]);

	    $name='etudiant.pdf';

		$this->deleteLastFile();

	    return $pdf->download($name);

	}

}


