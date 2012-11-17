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

//Initialisation des jeux;

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

?>