<?php
namespace lib\user;
use lib\db\Database;
use PDO;
/**
 * @author Karl
 */
class User {
    private $id = 0;
    private $login;
    private $password;
    private $name;
    private $surname;
    private $mail;
    
    public function __construct(){
    }
    private function hydrate(array $datas){
        foreach($datas as $key => $value){
            switch($key){
                case 'id':
                    $this->$key = (int) $value;
                    break;
                case 'login':
                case 'password':
                case 'surname':
                case 'name':
                case 'mail':
                    $this->$key = (string) $value;
                    break;
            }
        }
    }
    public function getId() {
        return $this->id;
    }
    private function setId($id){
        $this->id = $id;
    }
    public function getLogin() {
        return $this->login;
    }
    public function setLogin($login) {
        $this->login = (string) $login;
    }
    public function isPassword($password) {
        return ($this->password == hash('sha256', $password));
    }
    public function getHashedPassword() {
        return $this->password;
    }
    public function setPassword($password) {
        $this->password = hash('sha256', $password);
    }
    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = (string) $name;
    }
    public function getSurname() {
        return $this->surname;
    }
    public function setSurname($surname){
        $this->surname = (string) $surname;
    }
    public function getMail() {
        return $this->mail;
    }
    public function setMail($mail) {
        $this->mail = (string) $mail;
    }

    static public function countUsers(){
        $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM user_data');
        $req->execute();
        $count = $req->fetchColumn();
        $req->closeCursor();
        return $count;
    }
    static public function saveUser(User $user){
        //Aiguilleur Insert/Update
        $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM user_data WHERE id = :id');
        $req->bindvalue('id', $user->getId(), PDO::PARAM_INT);
        $req->execute();
        $count = $req->fetchColumn();
        $req->closeCursor();
        if($count == 0){
            $req = DataBase::getInstance()->prepare('INSERT INTO user_data (login, password, name, surname, mail) VALUES (:login, :password, :name, :surname, :mail)');
        }
        else{
            $req = DataBase::getInstance()->prepare('UPDATE user_data SET (login = :login, password = :password, name = :name, surname = :surname, mail = :mail) WHERE id = :id');
            $req->bindvalue('id', $user->getId(), PDO::PARAM_INT);
        }
        $req->bindValue('login', $user->getLogin(), PDO::PARAM_STR);
        $req->bindValue('password', $user->getHashedPassword(), PDO::PARAM_STR);
        $req->bindValue('name', $user->getName(), PDO::PARAM_STR);
        $req->bindValue('surname', $user->getSurname(), PDO::PARAM_STR);
        $req->bindValue('mail', $user->getMail(), PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
        if($count == 0){
            $user->setId(DataBase::getInstance()->lastInsertId());
        }
    }
    static public function getUsers(){
        $users = array();
        $req = DataBase::getInstance()->prepare('SELECT id, login, password, name, surname, mail FROM user_data');
        $req->execute();
        while($datas = $req->fetch()){
            $user = new User();
            $user->hydrate($datas);
            $users[] = $user;
        }
        $req->closeCursor();
        return $users;
    }
    static public function getUser($id){
        $req = DataBase::getInstance()->prepare('SELECT id, login, password, name, surname, mail FROM user_data WHERE id = :id');
        $req->bindvalue('id', $id, PDO::PARAM_INT);
        $req->execute();
        $datas = $req->fetch();
        $user = new User();
        $user->hydrate($datas);
        $req->closeCursor();
        return $user;
    }
}

?>
