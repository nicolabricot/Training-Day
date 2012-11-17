<?php
namespace lib;
use lib\content\Form;
use lib\content\Image;
use lib\content\Menu;
use lib\content\Message;
use lib\lan\Game;
use lib\lan\Schedule;
use lib\lan\Team;
use lib\lan\Tournament;
use lib\user\User;

spl_autoload_extensions('.php');
spl_autoload_register();

session_start();

//Initialisation des jeux pour la demo du front end;

$bf3 = new Game();
$bf3->setName('Battlefield 3');
$bf3->setDescription('Battlefield 3 est un FPS sur PC. A travers une campagne solo et une série de 6 missions coopératives, le jeu vous plonge dans un conflit moderne qui embrase les 4 coins du monde. Battlefield 3 propose en outre un mode multijoueur complet capable d\'accueillir 64 joueurs sur 9 maps qui s\'adaptent au mode de jeu choisi. Avec ses 4 classes customisables, ses armes modifiables, ses nombreux véhicules et son système d\'escouade, il s\'oriente principalement vers le jeu d\'équipe.');
$bf3->setCover('bf3.jpg');
$gw2 = new Game();
$gw2->setName('Guild Wars 2');
$gw2->setDescription('Guild Wars 2 est un jeu de rôle massivement multijoueur qui entraîne le joueur dans un univers heroïc-fantasy. Jouable sans abonnement, il permet de faire évoluer son personnage en combattant des monstres, mais aussi en affrontant d\'autres joueurs dans des combats en champs de bataille et dans un mode "Monde contre Monde" faisant s\'affronter 3 serveurs différents dans de grandes batailles impliquant de conquérir différents points d\'une gigantesque carte.');
$gw2->setCover('gw2.jpg');
$l4d = new Game();
$l4d->setName('Left for Dead');
$l4d->setDescription('Left 4 Dead sur PC est un FPS horrifique basé en grande partie sur la coopération. En effet, vous incarnez un personnage dans un groupe de quatre, mais les trois autres peuvent être dirigés par des amis. Echappez aux zombies en veillant donc les uns sur les autres. Pour corser le tout, le jeu offre la possibilité à quatre autres joueurs d\'incarner des morts-vivants pour sillonner la ville et traquer les héros.');
$l4d->setCover('l4d.jpg');

Game::saveGame($bf3);
Game::saveGame($gw2);
Game::saveGame($l4d);

$bf3Tour = new Tournament($bf3);
$bf3Tour->addSchedule('Matin', new Schedule(1353567600, 1353582000)); //22.11.2012 8h00 - 12h00
$bf3Tour->setName('Tournois Battlefield 3');
$bf3Tour->setDescription('Ruée en escouade 4vs4 sur carte aléatoires- Equipes de 4 personnes - Compte BF3 et DLC Close Quarters requis');

$l4dTour = new Tournament($l4d);
$l4dTour->addSchedule('Après-Midi', new Schedule(1353589200, 1353603600)); //22.11.2012 14h00 - 18h00
$l4dTour->setName('Tournois Battlefield 3');
$l4dTour->setDescription('Survie 4vs4 en 5 manches sur carte aléatoire - Equipes de 4 personnes - Jeu fourni sur place');

//Test des elements utilisés dans le frontend

$gamesList = Game::getGames();
$countGames = Game::countGames();

$tourList = Tournament::getTournaments();
$countTour = Tournament::countTournaments();

//Autres test du moteur, non implanté dans le frontend

$me = new User();
$me->setName('Karl');
$me->setSurname('Woditsch');
$me->setMail('k.woditsch@gmail.com');
$me->setLogin(uniqid());
$me->setPassword('1234');

User::saveUser($me);

$userList = User::getUsers();
$countUsers = User::countUsers();

$team = new Team();
$team->setName('Les winneurs');
$team->setDescription('Equipe de test');
$team->addPlayer($me);
$team->addTournament($bf3Tour);
$team->addTournament($l4dTour);

//Team::saveTeam($team);

$teamList = Team::getTeams();
$countTeams = Team::countTeams();
$myTeams = Team::getTeamsFromUser($me->getId());

echo 'Succes';

echo 'INIT TEST';
var_dump($bf3);
var_dump($gw2);
var_dump($l4d);
var_dump($bf3Tour);
var_dump($l4dTour);
echo 'USED IN FRONTEND TEST';
var_dump($gamesList);
var_dump($countGames);
var_dump($tourList);
var_dump($countTour);
echo 'NOT USED IN FRONTEND TEST';
var_dump($me);
var_dump($userList);
var_dump($countUsers);
var_dump($team);
var_dump($teamList);
var_dump($countTeams);
var_dump($myTeams);
echo 'END TEST';

?>