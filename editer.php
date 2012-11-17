<?php
use lib\db\Database;
use lib\content\TitleBar;
use lib\content\Form;
use lib\content\Message;
use lib\user\User;

$title = '';
$message = '';
$form = '';
$passMessage = '';
$pass = '';

//Title
$title = new TitleBar('Edition du compte');

//Action : Edition des informations
if(!empty($_POST['edit'])){
    if(!empty($_POST['name'])){
        if(empty($_POST['mail']) || filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) !== false){
            $name = htmlspecialchars($_POST['name']);
            $mail = (!empty($_POST['mail'])?htmlspecialchars($_POST['mail']):null);
            $phone = (!empty($_POST['phone'])?htmlspecialchars($_POST['phone']):null);
            $cellphone = (!empty($_POST['cellphone'])?htmlspecialchars($_POST['cellphone']):null);

            $req = DataBase::getInstance()->prepare('UPDATE user_data SET name = :name, mail = :mail, phone = :phone, cellphone = :cellphone WHERE id = :id');
            $req->bindValue('id', $_SESSION['id'], PDO::PARAM_INT);
            $req->bindValue('name', $name, PDO::PARAM_STR);
            $req->bindValue('mail', $mail, PDO::PARAM_STR);
            $req->bindValue('phone', $phone, PDO::PARAM_STR);
            $req->bindValue('cellphone', $cellphone, PDO::PARAM_STR);
            $req->execute();
            $req->closeCursor();

            $_USER->hydrate(array('name' => $name, 'mail' => $mail, 'phone' => $phone, 'cellphone' => $cellphone));

            $message = new Message('Informations modifiées.', Message::SUCCES);
        }
        else{
            $message = new Message('L\'adresse Email n\'a pas un format valide.', Message::ERROR);
        }
    }
    else{  
        $message = new Message('Un champ requis est vide.', Message::ERROR);
    }
}

//Formulaire : Edition des informations
$form = new Form();
$form->addFieldset('Informations personnelles');
$form->addText('name', 'Nom', true, $_USER->getName());
$form->addMail('mail', 'Adresse Email', false, $_USER->getMail());
$form->addTel('phone', 'Téléphone Fixe', false, $_USER->getPhone());
$form->addTel('cellphone', 'Téléphone Portable', false, $_USER->getCellphone());
$form->addSubmit('Modifier', 'edit');
$form->closeFieldset();

//Formulaire : Changemement de password
$pass = new Form();
$pass->addFieldset('Changemement de password');
$pass->addPassword('password', 'Password ', true, '');
$pass->addPassword('cpassword', 'Confirmation du password', true, '');
$pass->addSubmit('Valider', 'pass');
$pass->closeFieldset();

//Action : Changemement de password
if(!empty($_POST['pass'])){
    if(!empty($_POST['password'])){
        if($_POST['password'] == $_POST['cpassword']){
            $_USER->setPassword($_POST['password']);
            
            $req = DataBase::getInstance()->prepare('UPDATE user_data SET password = :password WHERE id = :id');
            $req->bindValue('id', $_SESSION['id'], PDO::PARAM_INT);
            $req->bindValue('password', $_USER->getHashedPassword(), PDO::PARAM_STR);
            $req->execute();
            $req->closeCursor();
            
            $passMessage = new Message('Password modifié.', Message::SUCCES);
        }
        else{
            $passMessage = new Message('Le password et la confirmation ne correspondent pas.', Message::ERROR);
        }
    }
    else{
        $passMessage = new Message('Aucun password renseigné.', Message::ERROR);
    }
}

//Affichage
echo $title, $message, $form, $passMessage, $pass;
?>
