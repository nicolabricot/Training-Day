<?php
use lib\content\Menu;
use lib\db\DataBase;
use lib\user\User;
use lib\user\Permissions;

//Protection csrf via token
if(empty($_SESSION['id']) || empty($_GET['token']) || $_GET['token'] != $_SESSION['token']){
    session_destroy();
    include('pages/login.php');
    exit;
}

//Utilisateur courrant
if($_SESSION['id'] == 'bypass'){
    $permissions = new Permissions();
    $permissions->addRight('parametres');
    $permissions->addRight('deconnection');
    $_USER = new User();
    $_USER->hydrate(array('name' => 'SuperAdmin'));
    $_USER->addGroup('SuperAdmin', $permissions->serialize());
}
else{
    $req = DataBase::getInstance()->prepare('SELECT id, login, password, name, mail, phone, cellphone FROM user_data WHERE id = :id');
    $req->bindvalue('id', $_SESSION['id'], PDO::PARAM_INT);
    $req->execute();
    if(!($datas = $req->fetch())){
        session_destroy();
        exit('Session invalide.');
    }
    $req->closeCursor();

    $req = DataBase::getInstance()->prepare('SELECT user_permissions.name, user_permissions.permissions FROM user_permissions 
                                           LEFT JOIN user_in ON user_in.permissions = user_permissions.id 
                                           WHERE user_in.user = :id');
    $req->bindvalue('id', $_SESSION['id'], PDO::PARAM_INT);
    $req->execute();
    $groups = array();
    while($group = $req->fetch()){
        $groups[$group['name']] = $group['permissions'];
    }
    $req->closeCursor();

    $_USER = new User();
    $_USER->hydrate($datas, $groups);
}

//Menu principal
$menuMain = new Menu();
$modules = new DirectoryIterator('modules');
foreach($modules as $module){
    if($module->isDot() || !$module->isDir())
        continue;
    $moduleName = $module->getBasename();
    if(empty($_GET['module']) && $_USER->hasRight($moduleName) && $moduleName != 'deconnection')
        $_GET['module'] = $moduleName;
    if($_USER->hasRight($moduleName))
        $menuMain->addLink($moduleName, $moduleName.'.php?token='.$_SESSION['token'], (!empty($_GET['module']) && $_GET['module'] == $moduleName));
}
if(empty($_GET['module']))
    $_GET['module'] = null;
$_GET['module'] = htmlspecialchars($_GET['module']);

//Menu du module courrant
$menuModule = new Menu();
try{
    $pagesDir = 'modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.'pages';
    if(is_dir($pagesDir)){
        $pages = new DirectoryIterator($pagesDir);
        foreach($pages as $page){
            if($page->isDot() || !$page->isFile())
                continue;
            $pageName = $page->getBasename('.php');
            if($_USER->hasRight($_GET['module'].'_'.$pageName))
                $menuModule->addLink($pageName, $_GET['module'].'-'.$pageName.'.php?token='.$_SESSION['token'], (!empty($_GET['page']) && $_GET['page'] == $pageName));
        }
    }
    $menuModule->addLink('<img src="img/icons/home.png" alt="accueil"/>', $_GET['module'].'.php?token='.$_SESSION['token'], !isset($_GET['page']));
}
catch(Exception $ex){
    $_GET['module'] = null;
}
$_GET['page'] = (isset($_GET['page'])?htmlspecialchars($_GET['page']):null);

//Contenu de la page du module
str_replace("\0", '', $_GET['module']); //Protection bytenull
str_replace(DIRECTORY_SEPARATOR, '', $_GET['module']); //Protection navigation
str_replace("\0", '', $_GET['page']);
str_replace(DIRECTORY_SEPARATOR, '', $_GET['page']);
$urlModule = 'modules'.DIRECTORY_SEPARATOR.$_GET['module'];
$urlPage = $_GET['module'].'.php';
if(!empty($_GET['page']))
    $urlPage = 'pages'.DIRECTORY_SEPARATOR.$_GET['page'].'.php';
$urlContent = $urlModule.DIRECTORY_SEPARATOR.$urlPage;
$contentModule = 'pages/error404.php';
if(file_exists($urlContent) && $_USER->hasRight($_GET['module'].'_'.$_GET['page']))
    $contentModule = $urlContent;

//Affichage
?><!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Trustinfo">
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <title>Projets Trustinfo</title>
        
        <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <link rel="stylesheet" media="screen" href="style.css" />
    </head>
    <body>
        <header>
            <nav id="header-menu">
                <?php echo $menuMain; ?>
            </nav>
        </header>
        <div id="content">
            <nav id="content-menu">
                <?php echo $menuModule; ?>
            </nav>
            <div id="content-page">
                <?php include($contentModule); ?>
                <div id="content-clear"></div>
            </div>
        </div>
        <footer>
            <small>Projets Trustinfo - <?php echo DataBase::countQuery(); ?> requête(s)</small>
        </footer>
        <script src="js/jquery.core.js" type="text/javascript"></script>
        <script src="js/jquery.easing.js" type="text/javascript"></script>
        <script src="js/jquery.shim.meter.js" type="text/javascript"></script>
        <script src="js/jquery.menu.js" type="text/javascript"></script>
        <script src="js/jquery.tip.js" type="text/javascript"></script>
        <script src="js/jquery.func.js" type="text/javascript"></script>
    </body>
</html>