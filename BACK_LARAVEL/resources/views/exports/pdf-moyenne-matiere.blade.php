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
            text-align: center;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: lightgray;
        }

        tr.ue th {
            background-color: rgb(134, 182, 162);
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
            text-align: center;
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
        <strong>Annee universitaire:</strong> {{ $data['annee'] }}
    </div>
    <div>
        <strong>Parcour:</strong> {{  $data['parcour']->parcour }}
    </div>

</header>

<table>
    <thead>
        <tr class="ue">
            <th>N°INSCRIPTION</th>
            <th>NOM</th>
            <th>PRENOM(S)</th>
            @foreach($data['header'] as $header)
            <th style="text-transform: uppercase" scope="col">{{$header->ue->ue}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>

        @foreach($data['items'] as $item)
        <tr>
            <td>{{ $item->numeroInscription }}</td>
            <td>{{ $item->etudiant->personne->nom }}</td>
            <td>{{ $item->etudiant->personne->prenom }}</td>
             @foreach ($item->moyenne_ues as $moyenne_ue)                 
                @if($data['form']!='Validation')
                <td>{{ $moyenne_ue->valeur }}</td>
                @else 
                <td>{{ $moyenne_ue->valeur>=10?'V':'NV' }}</td>
                @endif
             @endforeach
        </tr>
        @endforeach


    </tbody>
</table>

@endsection