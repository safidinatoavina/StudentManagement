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


<table>
    <thead style="text-transform:uppercase">
        <tr>
            <th colspan="5">{{ $header['parcour'] }}</th>
            <th colspan="5">{{ $header['annee'] }}</th>
        </tr>
        <tr>
            <th ></th>
            <th ></th>
            <th ></th>
            @foreach ($data as $key=>$item )
            @if ($key!=0)
                @php
                    continue;
                @endphp
            @endif
            <th colspan="{{ count($item['matieres'])+($item['validation']?2:1) }}">{{ $item['ue'] }}</th>
            @endforeach
        </tr>
        <tr>
            <th style="width: 3cm">N°INSCRIPTION</th>
            <th style="width: 3cm">NOM</th>
            <th style="width: 3cm">PRENOM(S)</th>
            @foreach ($data as $key=>$item )

                @if ($key!=0)
                    @php
                        continue;
                    @endphp
                @endif
                @foreach ($item['matieres'] as $matiere )
                <th style="width: 3cm">{{ $matiere['matiere'] }}</th>
                @endforeach
                <th style="width: 3cm">validation UE session normale</th>
                @if($item['validation'])
                <th style="width: 3cm">validation UE après rattrapage</th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $item )
        
            <tr>

                <td>{{ $item['numeroInscription'] }}</td>
                <td>{{ $item['nom'] }}</td>
                <td>{{ $item['prenom'] }}</td>
                @foreach ($item['matieres'] as $matiere )
                <td>{{ $matiere['validation'] }}</td>
                @endforeach
                <td>{{ $item['valeur_session_normal'] }}</td>
                @if ($item['validation'])
                    <td>{{ $item['validation'] }}</td>
                @endif

            </tr>

        @endforeach

    </tbody>
</table>

@endsection

