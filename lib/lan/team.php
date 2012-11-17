<?php
namespace lib\user;
use lib\db\DataBase;
/**
 * @author Karl
 */
class Team {
    private $id = 0;
	private $name;
	private $description;
	private array $players;

	public function __construct(){
	}

	public function hydrate(array $datas){
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

	public function addPlayer(User $user){
		$this->players[] = $user;
	}
	public function getPlayers(){
		return $this->players;
	}
	public 
	public function getName(){
		return $this->name;
	}
	public function setName($str){
		$this->name = (string) $str;
	}
	public function getDescription(){
		return $this->description;
	}
	public function setDescription($str){
		$this->description = (string) $str;
	}
	public function countPlayers(){
		return count($this->players);
	}

	public static function countTeams(){
	    $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM team');
		$req->execute();
		$count = $req->fetchColumn();
		$req->closeCursor();
		return $count;
	}
}
?>