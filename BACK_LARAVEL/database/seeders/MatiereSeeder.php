<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Matiere;
use App\Models\Parcour;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private function matieres()
    {
        return  [
            // "matiere"=>"Francais",
            // "matiere"=>"Algèbre linéaire ",
            // "matiere"=>"Analyse de Base",
            // "matiere"=>"Electrocinétique",
            // "matiere"=>"Mécanique générale  1",
            // "matiere"=>"Structure et propriétés de la matière",
            // "matiere"=>"Chimie physique des réactions et systèmes",
            // "matiere"=>"Initiation aux outils de travail universitaire",
            // "matiere"=>"Chimie Physique des réactions et systèmes",
            // "matiere"=>"La cellule unité du vivant",
            // "matiere"=>"Diversité du vivant",
            // "matiere"=>"Terre et vie",
            // "matiere"=>"Chimie physique pour SVT",
            // "matiere"=>"Mathématique pour SVT",
            // "matiere"=>"Physique pour SVT",
            // "matiere"=>"Diversité et organisation des végétaux",
            // "matiere"=>"Diversité et organisation des animaux",
            // "matiere"=>"Formation et évolution de la planète terre ",
            // "matiere"=>"Origine et histoire de la  vie évolution de la biosphère/Généralités en Anthropologie biologique",
            // "matiere"=>"Thermodynamique",
            // "matiere"=>"Topologie d'un espace métrique- Cas de Rn ",
            // "matiere"=>"Algèbre linéaire 2",
            // "matiere"=>"Géométrie dans  IRn et Applications  Différentiables",
            // "matiere"=>"Probabilité et variables aléatoires",
            // "matiere"=>"Introduction au Calcul Numérique et Algorithmique",
            // "matiere"=>"Optique, Electricité, Mécanique Générale",
            // "matiere"=>" Mathématiques 3, fondamentale",
            // "matiere"=>"Ondes électromagnétiques ",
            // "matiere"=>" Optique physique , fondamentale",
            // "matiere"=>"Electronique analogique",
            // "matiere"=>"Méthodes numériques et langages",
            // "matiere"=>"Algèbre et Analyse",
            // "matiere"=>"Ondes électromagnétiques",
            // "matiere"=>"Optique Physique",
            // "matiere"=>"Français ",
            // "matiere"=>"Anglais",
            // "matiere"=>"Chimie organique descriptive ",
            // "matiere"=>"Matériaux inorganiques",
            // "matiere"=>"Chimie nucléaire / Chimie Quantique",
            // "matiere"=>"Calculs  matriciels  et fonctions de plusieurs variables",
            // "matiere"=>"Ondes électromagnétiques /  Relativité Restreinte",
            // "matiere"=>"Mécanique analytique /  Mécanique quantique ",
            // "matiere"=>"Français / Anglais",
            // "matiere"=>"Classification des grands groupes végétaux et animaux",
            // "matiere"=>"Les transformations des molécules du vivant",
            // "matiere"=>"Physiologie animale, Pharmacologie ",
            // "matiere"=>"Nutrition minérale des végétaux",
            // "matiere"=>"Bases de l’analyse génétique ",
            // "matiere"=>"Renforcement des outils de communication",
            // "matiere"=>"Classification des grands groupes végétaux",
            // "matiere"=>"Classification des grands groupes animaux",
            // "matiere"=>"Physiologie neuromusculaire / Initiation à la Pharmacologie",
            // "matiere"=>"Cartographie",
            // "matiere"=>"Pétrolographie magmatique et métamorphique",
            // "matiere"=>"Minéralogie",
            // "matiere"=>"Pétrographie sédimentaire et Géologie de surface",
            // "matiere"=>"Mathématique appliquée à la géologie",
            // "matiere"=>"Ecosystème et Biodiversité",
            // "matiere"=>"Apprentissage et methodes de travail",
            // "matiere"=>"Pétrographie magmatique et métamorphique",
            // "matiere"=>"Pétrographie sédimentaire et Géologie de surface",
            // "matiere"=>"Mathématiques Appliquée à la géologie",
            // "matiere"=>"Cartographie topographique et géologique",
            // "matiere"=>"Eléments de géologie",
            // "matiere"=>"Base de Minéralogie et Pétrologie",
            // "matiere"=>"Faciès environnementaux Passé et Actuel",
            // "matiere"=>"Géodynamique externe",
            // "matiere"=>"Paléontologie: carrefour biologie-géologie",
            // "matiere"=>"Transversale: Apprentissage et méthodes de travail",
            // "matiere"=>"Algèbre",
            // "matiere"=>"Espace métrique et espace vectoriel normé",
            // "matiere"=>"Différentiabilité dans un Banach- Applications",
            // "matiere"=>"Mesure et intégration ",
            // "matiere"=>"Géométrie",
            // "matiere"=>"Analyse numérique et algorithmique",
            // "matiere"=>"Probabilité ",
            // "matiere"=>"Optimisation et analyse numérique",
            // "matiere"=>"Mécanique de Lagrange",
            // "matiere"=>"Algèbres appliquées",
            // "matiere"=>"Mesure et intégration",
            // "matiere"=>"Analyse réelle",
            // "matiere"=>"Algèbre Algorithmique",
            // "matiere"=>"Probabilité",
            // "matiere"=>"Intégration",
            // "matiere"=>"Méthode Numérique",
            // "matiere"=>"Mathématiques 5 : Probabilité - Statistiques",
            // "matiere"=>"Electronique numérique",
            // "matiere"=>"Traitement du Signal et Images",
            // "matiere"=>"Introduction à la Mécanique analytique",
            // "matiere"=>"Introduction à la Physique Quantique",
            // "matiere"=>"Algorithmiques et structures des données",
            // "matiere"=>"Probabilités et Statistique",
            // "matiere"=>"Traitement du signal et d'image",
            // "matiere"=>"Mécanique quantique",
            // "matiere"=>"Rédaction scientifique",
            // "matiere"=>"Chimie physique théorique",
            // "matiere"=>"Réactivités et Cinétique chimique",
            // "matiere"=>"Structures cristallines et propriétés chimiques",
            // "matiere"=>" Chimie Analytique 3",
            // "matiere"=>"Structure et Réactivité des fonctions en chimie organique",
            // "matiere"=>"Initiation à la chimie des Produits Naturels",
            // "matiere"=>"Synthèse organique",
            // "matiere"=>"Sol-Gel",
            // "matiere"=>"Méthode de validation des analyses chimiques",
            // "matiere"=>" Chimie physique théorique",
            // "matiere"=>"Sol-Gel ",
            // "matiere"=>"Méthode de validation des analyses chimiques ",
            // "matiere"=>"Enzymologie approfondie",
            // "matiere"=>"Techniques d’étude des biomolécules ",
            // "matiere"=>"Microbiologie approfondie",
            // "matiere"=>"Toxicologie générale",
            // "matiere"=>"Gestion d’entreprise : principes et organisation",
            // "matiere"=>"Outils logiciels dédiés à la biologie",
            // "matiere"=>"Techniques d’étude des biomolécules",
            // "matiere"=>"Habitat, sociologie et écologie des populations",
            // "matiere"=>"Génétique des populations ",
            // "matiere"=>"Botanique économique ",
            // "matiere"=>"Outils de communication et de création",
            // "matiere"=>"Croissance et développement des plantes",
            // "matiere"=>"Anatomie comparée et fonctionnelle des vertébrés ",
            // "matiere"=>"Biologie de la reproduction et du développement",
            // "matiere"=>"Physiologie de la reproduction",
            // "matiere"=>"Systématique des Angiospermes",
            // "matiere"=>"Dessins animaliers et botaniques",
            // "matiere"=>"Pétrologie métamorphique et microscopie",
            // "matiere"=>"Pétrologie magmatique et microscopie",
            // "matiere"=>"Géologie et développementet et Infrastructure géologique",
            // "matiere"=>"Géochimie de base et Evolution des continents",
            // "matiere"=>"Pédologie fondamentale et appliquée",
            // "matiere"=>"Environnement et développement durable",
            // "matiere"=>"Techniques de Prospection",
            // "matiere"=>"Minerais et Gisements, optionnelle",
            // "matiere"=>"Hydrologie de surface et soutérraine, optionnelle",
            // "matiere"=>" Introduction à la géotechnique, optionnelle",
            // "matiere"=>"Minerais et Gisements, Optionnelle",
            // "matiere"=>"Hydrologie de surface et souterraine, Optionnelle",
            // "matiere"=>"Introduction à la géotechnique, Optionnelle",
            // "matiere"=>"Probabilités appliquées et grandes déviations I",
            // "matiere"=>"Statistiques appliquées I ",
            // "matiere"=>"Distributions",
            // "matiere"=>"Algèbre appliquée I ",
            // "matiere"=>"Analyse mathématique I",
            // "matiere"=>"Algorithmique I",
            // "matiere"=>"Mécanique des Fluides ",
            // "matiere"=>"Mécanique des solides I: élasticité",
            // "matiere"=>"Mécanique Hamiltonienne I",
            // "matiere"=>"Analyse numérique I ",
            // "matiere"=>"Equations aux dérivées partielles I",
            // "matiere"=>"Géométrie symplectique et mécanique",
            // "matiere"=>"Méthode numérique I ",
            // "matiere"=>"EDP  I ",
            // "matiere"=>"Algorithmique I ",
            // "matiere"=>"Informatique I ",
            // "matiere"=>"Statistique appliquée I ",
            // "matiere"=>"Initiation à la combinatoire ",
            // "matiere"=>"Econométrie I ",
            // "matiere"=>"Optimisation I ",
            // "matiere"=>"Théorie des graphes I",
            // "matiere"=>"Théorie des jeux I ",
            // "matiere"=>"Techniques Mathématiques pour la Physique",
            // "matiere"=>"Physique statistique",
            // "matiere"=>"Physique quantique",
            // "matiere"=>"Vibrations et ondes",
            // "matiere"=>"Management ",
            // "matiere"=>"Communication en anglais",
            // "matiere"=>"Informatique",
            // "matiere"=>"Techniques mathématiques pour la Physique",
            // "matiere"=>"Génie génétique",
            // "matiere"=>"Analyse des données expérimentales en biologie ",
            // "matiere"=>"Investigations ethnobiologiques et matériels d’étude",
            // "matiere"=>"Biochimie pathologique",
            // "matiere"=>"Renforcement des langues de communication",
            // "matiere"=>"Mécanismes de régulation de la nutrition",
            // "matiere"=>"Anthropologie nutritionnelle",
            // "matiere"=>"Biotechnologies approfondies",
            // "matiere"=>"Biomasse",
            // "matiere"=>"Statistiques appliquées à la biologie",
            // "matiere"=>"Elaboration et gestion de projets de recherche et de développement",
            // "matiere"=>"Sciences de l’environnement, développement durable et conventions internationales",
            // "matiere"=>"Télédetection et SIG",
            // "matiere"=>"Biodiversité végétale tropicale et insulaire",
            // "matiere"=>"Méthodologie de la recherche",
            // "matiere"=>"Phylogénie et systématique des insectes",
            // "matiere"=>"Régulation des grandes fonctions physiologiques des insectes",
            // "matiere"=>"Pesticides",
            // "matiere"=>"Système nerveux et du système endocrine des insectes",
            // "matiere"=>"Biologie et écologie des milieux aquatiques",
            // "matiere"=>"Ecologie évolutive",
            // "matiere"=>"Dynamique des écosystèmes et des peuplements",
            // "matiere"=>"Crise de la biodiversité ",
            // "matiere"=>"Génétique évolutive",
            // "matiere"=>"Zoologie Approfondie",
            // "matiere"=>"GEOENVIRONNEMENTS : ENJEUX & PROBLEMATIQUES",
            // "matiere"=>"PROCESSUS SEDIMENTAIRE",
            // "matiere"=>"LES MILIEUX  LIMNIQUES, LITTORAUX ET MARINS",
            // "matiere"=>"CARTOGRAPHIE STATISTIQUE ET SIG ",
            // "matiere"=>"ANGLAIS ET ECOLE DE TERRAIN",
             
        ];
    }

    public function run()
    {
        Matiere::truncate();

        foreach($this->matieres() as $key=>$matiere){

            $parcours_id=Parcour::all()->map(function ($parc){
                return $parc->id;
            })->toArray();


            $users_id=User::all()->map(function ($user){
                return $user->id;
            })->toArray();

            $user=User::find($users_id[array_rand($users_id,1)]);

            $user->roles()->sync([3]);

            Matiere::create([
                'parcour_id'=>Parcour::find($parcours_id[array_rand($parcours_id,1)])->id,
                'ue_id'=>random_int(1,3),
                'user_id'=>$user->id,
                'matiere'=> $matiere,
                'coefficient'=>3,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

    }
}
