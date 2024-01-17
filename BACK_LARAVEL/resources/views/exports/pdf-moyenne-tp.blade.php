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

    <div>
        <strong style="text-decoration: underline">Parcour:</strong> {{ $parcour }}
    </div>
    <div>
        <strong style="text-decoration: underline" >Année universitaire:</strong> {{ annee()->valeur }}
    </div>
    <div>
        <strong style="text-decoration: underline" >TP:</strong> {{ $data->first()->tp->tp }}
    </div>

    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th style="width:4cm">N°INSCRIPTION</th>
                <th style="width:4cm">NOM</th>
                <th style="width:4cm">PRENOM(S)</th>
                <th style="width:4cm">{{ $is_validation?'VALIDATION':'NOTE' }}</th>
            </tr>
        </thead>
        <tbody>
             @foreach ($data as $note_tp)
            
                <tr>
                    <td >
                        {{ $note_tp->historique->numeroInscription }}
                    </td>
                    
                    <td>
                        {{ $note_tp->historique->etudiant->personne->nom }}
                    </td>

                    <td>
                        {{ $note_tp->historique->etudiant->personne->prenom }}
                    </td>

                    <td>
                        {{ $is_validation?($note_tp->valeur>=10?'V':'NV'):$note_tp->valeur }}
                    </td>

                </tr>

            @endforeach
        </tbody>
    </table>


@endsection

