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

        td,th{
            text-align: center;
        }

    </style>

@endpush

@section('body')

@foreach ($parcours as $parcour)

    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th colspan="5" ></th>
            </tr>
            <tr>
                <th colspan="5">PARCOUR: {{ $parcour->parcour }}</th>
            </tr>
            <tr>
                <th colspan="5" ></th>
            </tr>
            <tr>
                <th style="width:4cm">UE</th>
                <th style="width:4cm">CREDIT UE</th>
                <th style="width:4cm">ECUE</th>
                <th style="width:4cm">CREDIT ECUE</th>
                <th style="width:4cm">TP</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($parcour->ues as $ue_key=>$ue )

                @foreach ($ue->matieres as $matiere_key=>$matiere)
                
                    <tr>
                        <td >
                            {{ $ue->ue }}
                        </td>
                        
                        <td>
                            {{ $ue->credit }}
                        </td>

                        <td>{{ $matiere->matiere }}</td>

                        <td>{{ $matiere->coefficient }}</td>


                        <td>
                            @if ($matiere->tp)
                                {{ $matiere->tp->tp }}
                            @endif
                        </td>
                    </tr>

                @endforeach
                
            @endforeach
        </tbody>
    </table>

@endforeach


@endsection