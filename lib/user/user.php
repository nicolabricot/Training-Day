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
    
    public function __construct(){
        $this->groups = array();
    }
    public function hydrate(array $datas, array $groups = array()){
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
        foreach($groups as $group => $permissions){
            $this->addGroup($group, $permissions);
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
        $this->surname = $surname;
    }
    public function getMail() {
        return $this->mail;
    }
    public function setMail($mail) {
        $this->mail = (string) $mail;
    }

    public function getGroups(){
        return array_keys($this->groups);
    }
    public function addGroup($group, $permissions){
        if(!($permissions instanceof Permissions)){
            $acces = new Permissions();
            $acces->unserialize((string) $permissions);
        }
        else{
            $acces =& $permissions;
        }
        $this->groups[(string) $group] = $acces;
    }
    public function rmGroup($group){
        unset($this->groups[(string) $group]);
    }

    public function hasRight($str){
        foreach($this->groups as $group => $permissions){
            if($permissions->hasRight($str))
                return true;
        }
        return false;
    }
}

?>
