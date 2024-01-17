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
    header ul{
          list-style-type: none;
          margin: 0;
          padding: 0;
    }
    header li{
          margin: 0;
    }

    .footer-tab{
        margin-top: 15px;
    }

    .footer-tab div{
        font-weight: 700;
    }

    .super-title{
        text-align: center;
        padding: 20px;
        text-decoration: underline;
        font-weight: 700;
        font-size: 25px;
    }

    div.logo{
        position: absolute;
        right: -30px;
        top: -30px;
    }
    .logo img{
        width: 150px;
        height: 110px;
    }


</style>
@endpush

@section('body')


<header>

    <div class="logo">
        <img src="http://localhost:5173/logo/universite.png">
    </div>

	<ul>
        <li>
            <strong>Université d'Antananarivo</strong> 
        </li>
		<li>
			<strong>Annee universitaire:</strong> {{ $data['annee'] }}
		</li>
		<li>
			<strong>Nom:</strong> <span>{{ $data['nom'] }}</span>
		</li>
		<li>
			<strong>Prenom:</strong> <span>{{ $data['prenom'] }}</span>
		</li>
		<li>
			<strong>N°inscription:</strong> {{ $data['numeroInscription'] }}
		</li>
	</ul>
    
</header>

<div class="super-title">
	Relevé des notes
</div>

<table>
    <thead>

        <tr>
            <th>Matiere</th>
            <th>Semestre</th>
            <th>Note sur 20</th>
        </tr>

    </thead>
    <tbody>

        @foreach($data['notes'] as $note)
            <tr>
                <td>{{ $note->matiere->matiere }}</td>
                <td>{{ $note->matiere->semestre->semestre }}</td>
                <td>{{ $note->valeur }}</td>
            </tr>
        @endforeach

    </tbody>
</table>

<div class="footer-tab">
	<div>
        <span style="text-decoration: underline">TOTAL UE VALIDE:</span> 
        <strong>{{ $data['total_ue_valide'] }}</strong>
    </div> 
	<div>
        <span style="text-decoration: underline">MOYENNE:</span> 
        <strong>{{ $data['moyenne'] }}</strong>
    </div> 
</div>

@endsection
