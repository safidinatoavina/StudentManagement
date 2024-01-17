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
        <strong>Annee universitaire:</strong> {{ $data['header']['annee'] }}
    </div>
    <div>
        <strong>Parcour:</strong> {{  $data['header']['parcour'] }}
    </div>
    <div>
        <strong>Resultat:</strong> Final 
    </div>

</header>

<table>
    <thead>
        <tr>
            <th>N°INSCRIPTION</th>
            <th>NOM</th>
            <th>PRENOM(S)</th>
            <th>NOTE</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['liste'] as $value)
        <tr>
            <td>
                {{ $value['numeroInscription'] }}
            </td>
            <td>
                {{ $value['nom'] }}
            </td>
            <td>
                {{ $value['prenom'] }}
            </td>
            <td>
                @if ($data['type']=='moyenne')
                    {{ $value['moyenne'] }}
                @else
                    {{ $value['moyenne']>=10?'V':'NV' }}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
