<?php
namespace lib;
namespace lib\content;

spl_autoload_extensions('.php');
spl_autoload_register();

session_start();

//Template maitre, les pages supplémentaires sont à mettre dans le dossier pages/

// Menu principal
$masterMenu = new Menu();
$masterMenu->addlink('Accueil', '/');
$masterMenu->addlink('Tournoi', '/tournoi');
$masterMenu->addlink('Infos pratiques', '/infos-pratiques');

//Affichage
?><!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="EnsiLAN" />
        <meta http-equiv="X-UA-Compatible" content="chrome=1" />
        <meta name="viewport" content="width=device-width" />
        <meta name="robots" content="index, follow, archive" />
        
        <title><?php if(!empty($subTitle)) {echo $subTitle, ' &ndash; ';} ?>EnsiLAN</title>
        
        <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <link rel="stylesheet" media="screen" href="/css/style.css" />
        
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" /> 
		<link rel="icon" type="image/png" href="/favicon.png" />
    </head>
    
    <body>
        
        <header>
            <h1><a href="/" title="EnsiLAN">EnsiLAN</a></h1>
            <nav id="header-menu">
                <?php echo $masterMenu; ?>
            </nav>
        </header>
        
        <div id="content">
            <p>Hello world !</p>
        </div>
        
        <footer>
            <p>
                <span><a href="http://xid.ensisa.info/training-day">Training Day</a> organisé par le <a href="http://xid.ensisa.info">XID</a>.
                <br />&copy; 2012 &ndash; EnsiLAN par l'équipe FKNQR&sup2;</p>
                </span>
        </footer>
        
    </body>
</html>

