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
        <strong>Parcour:</strong> {{  $data['parcour'] }}
    </div>

</header>

<table>
    <thead style="text-transform: uppercase">
        <tr class="ue">
            <th scope="col"></th>
            <th scope="col"></th>
            @foreach($data['header'] as $ue=>$header)
            <th scope="col" colspan="{{count($header)+2}}">{{$ue}}</th>
            @endforeach
        </tr>
        <tr>
            <th>Nom</th>
            <th>Prenom(s)</th>
            @foreach($data['header'] as $ue=>$header)
                @foreach($header as $matiere)
                <th>{{ $matiere->matiere }}</th>
                @endforeach
                <th>Moyenne ue</th>
                <th>Validation ue</th>
            @endforeach
        </tr>
    </thead>
    <tbody>

        @foreach($data['items'] as $historique_id)
        <tr>
            <td>{{ $historique_id['personne']['nom'] }}</td>
            <td>{{ $historique_id['personne']['prenom'] }}</td>
            @foreach($data['header'] as $ue=>$header)
                @foreach($header as $matiere)
                    <td>{{ $matiere->moyenne_matieres->where('historique_id',$historique_id['id'])->first()->valeur }}</td>
                @endforeach
                <td>{{ \App\Models\MoyenneUe::where('historique_id',$historique_id['id'])->where('ue_id',$matiere->ue->id)->first()->valeur }}</td>
                <td>{{ \App\Models\MoyenneUe::where('historique_id',$historique_id['id'])->where('ue_id',$matiere->ue->id)->first()->valeur>=10? 'V':'NV' }}</td>
            @endforeach
        </tr>
        @endforeach


    </tbody>
</table>

@endsection
