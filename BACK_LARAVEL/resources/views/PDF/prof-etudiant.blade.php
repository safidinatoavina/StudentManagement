@extends('layout.layout')

@push('head')

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: lightgray;
        }

        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .student-info .left {
            font-size: 12px;
        }

        .student-info .right {
            font-size: 12px;
            text-align: right;
        }

        .footer {
            font-size: 12px;
            text-align: center;
        }

        header strong{
            text-decoration:underline;
        }

        header{
            margin-bottom: 18px;
        }

    </style>

@endpush

@section('body')

<header>

    <div>
        <strong>Annee universitaire:</strong> {{ $data['list'][0]->annee_universitaire->valeur }}
    </div>
    <div>
        <strong>Parcour:</strong> {{  $data['list'][0]->parcour->parcour }}
    </div>
    
    @if(in_array('note',$data['header']) || in_array('validation',$data['header']))

        <div>
            <strong>semestre:</strong> {{  \App\Models\Semestre::find($data['en_cours']->semestre_id)->semestre }}
        </div>
        <div>
            <strong>session:</strong> {{  \App\Models\Session::find($data['en_cours']->session_id)->session }}
        </div>

    @endif

</header>

<table>
    <thead>
        <tr>
            <th>N°INSCRIPTION</th>
            <th>NOM</th>
            <th>PRENOM(S)</th>
            @if(in_array('validation',$data['header']))
            <th>VALIDATION</th>
            @endif
            @if(in_array('note',$data['header']))
            <th>NOTE</th>
            @endif
        </tr>
    </thead>
    <tbody>

        @foreach ($data['list'] as $historique )
            <tr>

                <td>{{ $historique->numeroInscription }}</td>
                <td>{{ $historique->etudiant->personne->nom }}</td>
                <td>{{ $historique->etudiant->personne->prenom }}</td>

                @if(in_array('validation',$data['header']))
                <td>
                    @if($historique->notes->count())
                        @if($historique->notes->first()->is_set)
                            {{ $historique->notes->first()->valeur >= 10? 'V':'NV' }}
                        @else
                            X
                        @endif
                    @endif
                </td>
                @endif

                @if(in_array('note',$data['header']))
                <td>
                    @if($historique->notes->count())
                        @if($historique->notes->first()->is_set)
                            {{ $historique->notes->first()->valeur }}
                        @else
                            X
                        @endif
                    @endif
                </td>
                @endif

            </tr>
        @endforeach

    </tbody>
</table>

@endsection

