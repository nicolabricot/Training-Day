<?php
namespace lib\lan;
use lib\db\DataBase;
use PDO;
/**
 * @author Karl
 */
class Tournament {
    private $id = 0;
	private $name;
	private $description;
	private $schedules;
	private $game;

	public function __construct(Game $game){
		$this->game = $game;
	}

	private function hydrate(array $datas){
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

	public function getId(){
		return $this->id;
	}
	private function setId($id){
		$this->id = $id;
	}
	public function addSchedule($label, Schedule $schedule){
		$this->schedules[$label] = $schedule;
	}
	private function loadSchedules(){
		$req = DataBase::getInstance()->prepare('SELECT start, stop FROM tournament_schedule WHERE tournament = :id');
    	$req->bindvalue('id', $this->getId(), PDO::PARAM_INT);
    	$req->execute();
    	$i = 0;
    	while($datas = $req->fetch()){
    		$this->addSchedule('load'.$i, new Schedule($datas['start'], $datas['stop']));
    		$i++;
    	}
    	$req->closeCursor();
	}
	public function getSchedules(){
		return $this->schedules;
	}
	public function rmSchedule($label){
		unlink($this->schedules[$label]);
	}
	public function inSchedule($time){
		foreach($this->schedules as $schedule){
			if($schedule->isIn($time)){
				return true;
			}
		}
		return false;
	}
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
	public function getGame(){
		return $this->game;
	}

	static public function countTournaments(){
	    $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM tournament');
		$req->execute();
		$count = $req->fetchColumn();
		$req->closeCursor();
		return $count;
	}

	static public function saveTournament(Tournament $tournament){
		//Aiguilleur Insert/Update
	    $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM tournament WHERE id = :id');
    	$req->bindvalue('id', $tournament->getId(), PDO::PARAM_INT);
		$req->execute();
		$count = $req->fetchColumn();
		$req->closeCursor();
		if($count == 0){
	    	$req = DataBase::getInstance()->prepare('INSERT INTO tournament (name, description) VALUES (:name, :description)');
		}
		else{
	    	$req = DataBase::getInstance()->prepare('UPDATE tournament SET (name = :name, description = :description) WHERE id = :id');
    		$req->bindvalue('id', $tournament->getId(), PDO::PARAM_INT);
		}
    	$req->bindValue('name', $tournament->name, PDO::PARAM_STR);
    	$req->bindValue('description', $tournament->description, PDO::PARAM_STR);
    	$req->execute();
    	$req->closeCursor();
		if($count == 0){
			$tournament->setId(DataBase::getInstance()->lastInsertId());
		}
    	//Jeu associé
    	$req = DataBase::getInstance()->prepare('DELETE FROM tournament_game WHERE tournament = :id');
    	$req->bindvalue('id', $tournament->getId(), PDO::PARAM_INT);
		$req->execute();
    	$req->closeCursor();
		$req = DataBase::getInstance()->prepare('INSERT INTO tournament_game (game, tournament) VALUES (:game, :tournament)');
    	$req->bindvalue('game', $tournament->getGame()->getId(), PDO::PARAM_INT);
    	$req->bindvalue('tournament', $tournament->getId(), PDO::PARAM_INT);
		$req->execute();
    	$req->closeCursor();
    	//Horaires associés
    	Schedule::deleteSchedules($tournament->getId());
		foreach($tournament->getSchedules() as $schedule){
			Schedule::saveSchedule($tournament->getId(), $schedule);
		}
	}
	static public function getTournament($id){
		$req = DataBase::getInstance()->prepare('SELECT id, name, description, game FROM tournament LEFT JOIN tournament_game ON tournament = id WHERE id = :id');
    	$req->bindvalue('id', $id, PDO::PARAM_INT);
    	$req->execute();
    	$datas = $req->fetch();
    	$req->closeCursor();
		$tournament = new Tournament(Game::getGame($datas['game']));
		$tournament->hydrate($datas);
    	$tournament->loadSchedules();
    	return $tournament;
	}
	static public function getTournaments(){
		$tournaments = array();
    	$req = DataBase::getInstance()->prepare('SELECT id, name, description, game FROM tournament LEFT JOIN tournament_game ON tournament = id');
    	$req->execute();
    	while($datas = $req->fetch()){
    		$toutnament = new Tournament(Game::getGame($datas['game']));
    		$toutnament->hydrate($datas);
    		$toutnament->loadSchedules();
    		$tournaments[] = $toutnament;
    	}
    	$req->closeCursor();
    	return $tournaments;
	}
	static public function getTournamentsFromTeam($id){
		$tournaments = array();
    	$req = DataBase::getInstance()->prepare('SELECT id, name, description, game FROM tournament LEFT JOIN team_inscription ON tournament = id WHERE team = :id');
    	$req->bindvalue('id', $id, PDO::PARAM_INT);
    	$req->execute();
    	while($datas = $req->fetch()){
    		$toutnament = new Tournament(Game::getGame($datas['game']));
    		$toutnament->hydrate($datas);
    		$toutnament->loadSchedules();
    		$tournaments[] = $toutnament;
    	}
    	$req->closeCursor();
    	return $tournaments;
	}
}
?>