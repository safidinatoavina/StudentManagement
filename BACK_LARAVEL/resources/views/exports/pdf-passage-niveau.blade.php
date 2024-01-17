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
        <strong>Annee universitaire:</strong> {{ $data['annee'] }}
    </div>

</header>

<table>
    <thead>
        <tr>
            <th>N°INSCRIPTION</th>
            <th>NOM</th>
            <th>PRENOM(S)</th>
            <th>UE OBTENUE</th>
            <th>CREDIT OBTENUE</th>
            <th>MOYENNE</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data['items'] as $note )
            <tr>
                <td>{{ $note['numeroInscription'] }}</td>
                <td>{{ $note['etudiant']['personne']['nom'] }}</td>
                <td>{{ $note['etudiant']['personne']['prenom'] }}</td>
                <td>{{ $note['moyenne_annee']['total_ue_valide'] }}</td>
                <td>{{ $note['moyenne_annee']['total_credit'] }}</td>
                <td>{{ $note['moyenne_annee']['valeur'] }}</td>

            </tr>
        @endforeach

    </tbody>
</table>

@endsection