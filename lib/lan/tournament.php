<?php
namespace lib\user;
use lib\db\DataBase;
/**
 * @author Karl
 */
class Tournament {
    private $id = 0;
	private $name;
	private $description;
	private array $schedules;
	private $game;

	public function __construct((Game) $game){
		$this->game = $game;
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

	public function addSchedule($label, Schedule $schedule){
		$this->schedules[$label] = $schedule;
	}
	public function getSchedules(){
		return $this->schedules;
	}
	public function rmSchedule($label){
		unlink($this->schedules[$label]);
	}
	public function getName(){
		return $this->name;
	}
	public function setName($str){
		$this->name = (string) $str;
	}
	public function getDescription(){
		return $this->name;
	}
	public function setDescription($str){
		$this->name = (string) $str;
	}
	public function getGame(){
		return $this->game;
	}

	public function countPlayers(){
		return count($this->players);
	}

	public static function countTournaments(){
	    $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM tournament');
		$req->execute();
		$count = $req->fetchColumn();
		$req->closeCursor();
		return $count;
	}
}
?>