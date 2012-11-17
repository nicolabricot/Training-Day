<?php
use lib\db\DataBase;
use lib\content\Form;
use lib\content\Message;
use lib\content\Table;
use lib\content\TitleBar;
use lib\content\Pages;
use lib\content\Search;
use lib\user\User;

$title = '';
$search = '';
$pages = '';
$list = '';

//Title
$title = new TitleBar('Contacts');

//Search
$search = new Search('compte-contacts.php?token='.$_SESSION['token']);

//Liste : Utilisateurs
$search->addColumn('name');
$search->addColumn('mail');
$search->addColumn('phone');
$search->addColumn('cellphone');

$req = DataBase::getInstance()->prepare('SELECT count(id) FROM user_data '.$search->getWhereLikes());
$search->bindSearch($req);
$req->execute();
$count = $req->fetchColumn();
$req->closeCursor();

$pages = new Pages('compte-contacts.php?token='.$_SESSION['token'], $count);

$list = new Table();
$list->addHeader('Nom', 'Email', 'Tel. Fixe', 'Tel. Portable');

$req = DataBase::getInstance()->prepare('SELECT name, mail, phone, cellphone FROM user_data '.$search->getWhereLikes().' ORDER BY name ASC LIMIT :first, :nb');
$req->bindValue('first', $pages->getFirstElement(), PDO::PARAM_INT);
$req->bindValue('nb', $pages->getNumElement(), PDO::PARAM_INT);
$search->bindSearch($req);
$req->execute();
while($data = $req->fetch()){
    $list->addContent($data['name'], $data['mail'], $data['phone'], $data['cellphone']);
}
$req->closeCursor();

echo $title, $search, $pages, $list, $pages;
?>
