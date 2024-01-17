<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">


    </head>
    <body>
  
    <table class="table table-bordered">
        <thead style="text-transform:uppercase">
            <tr>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                @foreach($data['data'] as $nom_ue=>$matieres)
                    @php
                        $nombre_tp=0;
                        
                        $option=$matieres->first()->ue->option;
                        foreach ($matieres as $matiere) {
                            if($matiere->tp)
                                $nombre_tp++;
                        }
                    @endphp
                @if (!$data['is_validation'])
                    <th scope="col" colspan="{{3*count($matieres)+1 /* pour moyenne ue  */ +$nombre_tp}}">{{$nom_ue}} @if($option) (OPTIONEL) @endif</th>
                @else
                    <th scope="col" colspan="{{count($matieres)+1 /* pour moyenne ue  */ }}">{{$nom_ue}} @if($option) (OPTIONEL) @endif</th>
                @endif

                @endforeach
            </tr>
            <tr>
                <th style="width:4cm">N°INSCRIPTION</th>
                <th style="width:4cm">NOM</th>
                <th style="width:4cm">PRENOM(S)</th>
                <th style="width:4cm">STATUT</th>
                @foreach($data['data'] as $nom_ue=>$matieres)

                    @foreach($matieres as $matiere)
                    
                        @if(!$data['is_validation'])
                            <th style="width:4cm">{{ $matiere->matiere."(".\App\Models\Session::where('id',2)->first()->session.")" }}</th>
                            <th style="width:4cm">{{ $matiere->matiere."(".\App\Models\Session::where('id',1)->first()->session.")" }}</th>
                            @if($matiere->tp)
                            <th style="width:4cm"> (TP): {{ $matiere->tp->tp }} </th>
                            @endif
                            
                            <th style="width:4cm">MOYENNE ECUE</th>
                        @else 

                            <th style="width:4cm">VALIDATION {{ $matiere->matiere }}</th>

                        @endif

                    @endforeach

                     @if(!$data['is_validation'])
                        <th style="width:3cm">MOYENNE UE</th>
                     @else
                        <th style="width:3cm">VALIDATION UE</th>
                     @endif


                @endforeach
                <th style="width:3cm"></th>
                <th style="width:5cm">MOYENNE SEMESTRE</th>
                <th style="width:5cm">TOTAL CREDIT OBTENUE</th>
                <th style="width:5cm">TOTAL UE OBTENUE</th>
            </tr>
        </thead>
        <tbody>

            @foreach($data['historiques'] as $historique)
            <tr>
                <td>{{ $historique->numeroInscription}}</td>
                <td>{{ $historique->etudiant->personne->nom }}</td>
                <td>{{ $historique->etudiant->personne->prenom }}</td>
                <td>{{ $historique->status->abreviation }}</td>
                @foreach($data['data'] as $nom_ue=>$matieres)
                    @foreach($matieres as $matiere)

                    @php
                        $note_matiere=$matiere->moyenne_matieres->where('historique_id',$historique->id)->first();
                        $note_normal=$matiere->notes->where('historique_id',$historique->id)->where('session_id',2)->first();
                        $note_rattrapage=$matiere->notes->where('historique_id',$historique->id)->where('session_id',1)->first();
                    @endphp

                    @if(!$data['is_validation'])

                        <td>{{ $note_normal->is_set?($data['is_validation']?($note_normal->valeur>=10?'V':'NV'):$note_normal->valeur):'X' }}</td>
                        <td>{{ $note_rattrapage->is_set?($data['is_validation']?($note_rattrapage->valeur>=10?'V':'NV'):$note_rattrapage->valeur):'X' }}</td>
                        {{-- si le note a de tp --}}
                        @if ($matiere->tp)
                            @php
                                $note_tp=$matiere->tp->note_tps->where('historique_id',$historique->id)
                            @endphp
                            <td> {{ $note_tp->first()->is_set?($data['is_validation']?($note_tp->first()->valeur>=10?'V':'NV'):$note_tp->first()->valeur):'X' }} </td>
                        @endif

                    @endif

                    {{-- moyenne ecue --}}
                    @php
                        $moyenne_matiere=$matiere->moyenne_matieres->where('historique_id',$historique->id)->first();
                    @endphp
                    <td>{{ $data['is_validation']?($moyenne_matiere->valeur>=10?'V':'NV'):$moyenne_matiere->valeur }}</td>
                    @endforeach
                    
                    @php
                        $note_ue=$matiere->ue->moyenne_ues->where('historique_id',$historique->id)->where('semestre_id',$data['semestre']->id) ->first();
                    @endphp

                    <td>{{ $data['is_validation']?($note_ue->valeur>=10?'V':'NV'):$note_ue->valeur }}</td>

                @endforeach
                <td></td>
                <td>{{ $historique->moyenne_semestres->where('semestre_id',$data['semestre']->id)->first()->valeur }}</td>
                <td>{{ $historique->moyenne_semestres->where('semestre_id',$data['semestre']->id)->first()->total_credit }}</td>
                <td>{{ $historique->moyenne_semestres->where('semestre_id',$data['semestre']->id)->first()->total_ue_valide }}</td>
            </tr>
            @endforeach

 
        </tbody>
    </table>

    </body>
</html>
