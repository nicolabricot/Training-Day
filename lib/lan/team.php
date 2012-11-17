<?php
namespace lib\lan;
use lib\db\DataBase;
use lib\user\User;
/**
 * @author Karl
 */
class Team {
    private $id = 0;
	private $name;
	private $description;
	private array $players;
	private array $inscriptions;

	public function __construct(){
		$this->players = array();
		$this->inscriptions = array();
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
	private function loadPlayers(){
        $req = DataBase::getInstance()->prepare('SELECT id, login, password, name, surname, mail FROM user_data LEFT JOIN team_player ON player = id WHERE team = :id');
        $req->bindvalue('id', $id, PDO::PARAM_INT);
        $req->execute();
        while($datas = $req->fetch()){
            $user = new User();
            $user->hydrate($datas);
            $this->players[] = $user;
        }
        $req->closeCursor();
	}
	public function getId(){
		return $this->id;
	}
	public function addPlayer(User $user){
		$this->players[] = $user;
	}
	public function getPlayers(){
		return $this->players;
	}
	public function rmPlayer($id){
		$players = array();
		foreach($this->players as $player){
			if($player->id != $id){
				$players[] = $player;
			}
		}
		$this->players = $players;
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
	public function countPlayers(){
		return count($this->players);
	}
	private function loadTournaments(){
		$req = DataBase::getInstance()->prepare('SELECT tournament FROM tournament_schedule WHERE tournament = :id');
    	$req->bindvalue('id', $tournament->getId(), PDO::PARAM_INT);
    	$req->execute();
    	$i = 0;
    	while($datas = $req->fetch()){
    		$this->addSchedule('load'.$i, new Schedule($datas['start'], $datas['stop']));
    		$i++;
    	}
    	$req->closeCursor();
	}
	public function addTournament(Tournament $tournament){
		$this->inscriptions[] = $tournament;
	}
	public function getTournaments(){
		return $this->inscriptions;
	}

	static public function countTeams(){
	    $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM team');
		$req->execute();
		$count = $req->fetchColumn();
		$req->closeCursor();
		return $count;
	}
	static public function saveTeam(Team $team){
		//Aiguilleur Insert/Update
	    $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM team WHERE id = :id');
    	$req->bindvalue('id', $team->getId(), PDO::PARAM_INT);
		$req->execute();
		$count = $req->fetchColumn();
		$req->closeCursor();
		if($count == 0){
	    	$req = DataBase::getInstance()->prepare('INSERT INTO team (name, description) VALUES (:name, :description)');
		}
		else{
	    	$req = DataBase::getInstance()->prepare('UPDATE team SET (name = :name, description = :description) WHERE id = :id');
    		$req->bindvalue('id', $team->getId(), PDO::PARAM_INT);
		}
    	$req->bindValue('name', $team->name, PDO::PARAM_STR);
    	$req->bindValue('description', $team->description, PDO::PARAM_STR);
    	$req->execute();
    	$req->closeCursor();
    	//Liens utilisateurs
    	$req = DataBase::getInstance()->prepare('DELETE FROM team_player WHERE team = :id');
    	$req->bindvalue('id', $team->getId(), PDO::PARAM_INT);
		$req->execute();
    	$req->closeCursor();
		$req = DataBase::getInstance()->prepare('INSERT INTO team_player (player, team) VALUES (:player, :team)')
		$req->bindvalue('team', $team->getId(), PDO::PARAM_INT);
		foreach($team->getPlayers() as $player){
    		$req->bindvalue('player', $player->getId(), PDO::PARAM_INT);
    	}
		$req->execute();
    	$req->closeCursor();
    	//Inscriptions
    	$req = DataBase::getInstance()->prepare('DELETE FROM team_inscription WHERE team = :id');
    	$req->bindvalue('id', $team->getId(), PDO::PARAM_INT);
		$req->execute();
    	$req->closeCursor();
		$req = DataBase::getInstance()->prepare('INSERT INTO team_inscription (tournament, team) VALUES (:tournament, :team)')
		$req->bindvalue('team', $team->getId(), PDO::PARAM_INT);
		foreach($team->getTournaments() as $tournament){
    		$req->bindvalue('tournament', $tournament->getId(), PDO::PARAM_INT);
    	}
		$req->execute();
    	$req->closeCursor();
	}

	static public function getTeam($id){
		$req = DataBase::getInstance()->prepare('SELECT id, name, description FROM team WHERE id = :id');
    	$req->bindvalue('id', $id, PDO::PARAM_INT);
    	$req->execute();
		$team = new Team();
		$team->hydrate($datas);
		$team->loadPlayers();
    	$req->closeCursor();
    	return $team;
	}
	static public function getTeams(){
		$teams = array();
    	$req = DataBase::getInstance()->prepare('SELECT id, name, description FROM team');
    	$req->execute();
    	while($datas = $req->fetch()){
			$team = new Team();
			$team->hydrate($datas);
			$team->loadPlayers();
			$team->loadTournaments();
    		$teams[] = $team;
    	}
    	$req->closeCursor();
    	return $teams;
	}
	static public function getTeamsFromTournament($id){
		$teams = array();
    	$req = DataBase::getInstance()->prepare('SELECT id, name, description FROM team LEFT JOIN team_inscription ON team = id WHERE tournament = :id');
    	$req->bindvalue('id', $id, PDO::PARAM_INT);
    	$req->execute();
    	while($datas = $req->fetch()){
			$team = new Team();
			$team->hydrate($datas);
			$team->loadPlayers();
			$team->loadTournaments();
    		$teams[] = $team;
    	}
    	$req->closeCursor();
    	return $teams;
	}
	static public function getTeamsFromUser($id){
		$teams = array();
    	$req = DataBase::getInstance()->prepare('SELECT id, name, description FROM team LEFT JOIN team_player ON team = id WHERE player = :id');
    	$req->bindvalue('id', $id, PDO::PARAM_INT);
    	$req->execute();
    	while($datas = $req->fetch()){
			$team = new Team();
			$team->hydrate($datas);
			$team->loadPlayers();
			$team->loadTournaments();
    		$teams[] = $team;
    	}
    	$req->closeCursor();
    	return $teams;
	}
}
?>