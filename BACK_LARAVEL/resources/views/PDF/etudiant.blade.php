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
        <strong>Annee universitaire:</strong> {{ $data[0]->historique->annee_universitaire->valeur }}
    </div>


</header>

<table>
    <thead>
        <tr>
            <th>NOM</th>
            <th>PRENOM(S)</th>
            <th>PARCOUR</th>
            <th>ECUE</th>
            <th>STATUS</th>
            <th>NOTE</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $note )
            <tr>
                <td>{{ $note->historique->etudiant->personne->nom }}</td>
                <td>{{ $note->historique->etudiant->personne->prenom }}</td>
                <td>{{ $note->historique->parcour->parcour }}</td>
                <td>{{ $note->matiere->matiere }}</td>
                <td>{{ $note->historique->status->valeur }}</td>
                <td>{{ $note->valeur }}</td>
            </tr>
        @endforeach

    </tbody>
</table>

@endsection