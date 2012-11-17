<?php
namespace lib\user;
use lib\db\DataBase;
/**
 * @author Karl
 */
class Team {
	private $name;
	private $description;

	public __construct(){
	}

	public hydrate(array $datas){
		foreach($datas as $key => $value){
            switch($key){
                case 'id':
                    $this->$key = (int) $value;
                    break;
                case 'name':
                case 'description':
                    $this->$key = (string) $value;
                    break;
            }
        }
	}

	public function getName(){
		return $this->name;
	}
	public function setName($str){
		$this->name = $str;
	}
	public function getDescription(){
		return $this->name;
	}
	public function setDescription($str){
		$this->name = $str;
	}

	public static function countPlayers(){
	    $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM user_data');
		$req->execute();
		$count = $req->fetchColumn();
		$req->closeCursor();
		return $count;
	}
}
?>