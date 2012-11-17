<?php
namespace lib\user;
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
    private $groups;
    
    public function __construct(){
        $this->groups = array();
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
        $users = array();
        $req = DataBase::getInstance()->prepare('SELECT id, login, password, name, surname, mail FROM user_data WHERE id = :id');
        $req->bindvalue('id', $id, PDO::PARAM_INT);
        $req->execute();
        while($datas = $req->fetch()){
            $user = new User();
            $user->hydrate($datas);
            $users[] = $user;
        }
        $req->closeCursor();
        return $users;
    }
}

?>
