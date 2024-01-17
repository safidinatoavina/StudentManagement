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
    <div>
        <strong>Semestre:</strong> {{ $data['semestre'] }}
    </div>


</header>

<table>
    <thead>
        <tr>
            <th>N°INSCRIPTION</th>
            <th>NOM</th>
            <th>PRENOM(S)</th>
            <th>MOYENNE</th>
            <th>TOTAL UE VALIDE</th>
            <th>TOTAL CREDIT OBTENUE</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data['items'] as $note )
            <tr>
                <td>{{ $note['numeroInscription'] }}</td>
                <td>{{ $note['nom'] }}</td>
                <td>{{ $note['prenom'] }}</td>
                <td>{{ $data['form']=='validation'?$note['validation']:$note['moyenne'] }}</td>
                <td>{{ $note['total_ue_valide'] }}</td>
                <td>{{ $note['total_credit'] }}</td>
            </tr>
        @endforeach

    </tbody>
</table>

@endsection