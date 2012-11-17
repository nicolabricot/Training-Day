<?php
namespace lib\lan;
use lib\db\DataBase;
/**
 * @author Karl
 */
class Game {
    private $id = 0;
	private $name;
	private $description;
	private $cover;

	public function __construct(){
	}

	private function hydrate(array $datas){
		foreach($datas as $key => $value){
            switch($key){
                case 'id':
                    $this->$key = (int) $value;
                    break;
                case 'name':
                case 'description':
                case 'cover':
                    $this->$key = (string) $value;
                    break;
            }
        }
	}

	public function getId(){
		return $this->id;
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
	public function getCover(){
		return $this->cover;
	}
	public function setCover($str){
		$this->cover = (string) $str;
	}

	static public function countGames(){
	    $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM game');
		$req->execute();
		$count = $req->fetchColumn();
		$req->closeCursor();
		return $count;
	}
	static public function saveGame(Game $game){
	    $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM game WHERE id = :id');
    	$req->bindvalue('id', $game->getId(), PDO::PARAM_INT);
		$req->execute();
		$count = $req->fetchColumn();
		$req->closeCursor();
		if($count == 0){
	    	$req = DataBase::getInstance()->prepare('INSERT INTO game (name, description, cover) VALUES (:name, :description, :cover)');
		}
		else{
	    	$req = DataBase::getInstance()->prepare('UPDATE game SET (name = :name, description = :description, cover = :cover) WHERE id = :id');
    		$req->bindvalue('id', $game->getId(), PDO::PARAM_INT);
		}
    	$req->bindValue('name', $game->name, PDO::PARAM_STR);
    	$req->bindValue('description', $game->description, PDO::PARAM_STR);
    	$req->bindValue('cover', $game->cover, PDO::PARAM_STR);
    	$req->execute();
    	$req->closeCursor();
	}
	static public function getGame($id){
		$req = DataBase::getInstance()->prepare('SELECT id, name, description, cover FROM game WHERE id = :id');
    	$req->bindvalue('id', $id, PDO::PARAM_INT);
    	$req->execute();
		$game = new Game();
		$game->hydrate($datas);
    	$req->closeCursor();
    	return $game;
	}
	static public function getGames(){
		$games = array();
    	$req = DataBase::getInstance()->prepare('SELECT id, name, description, cover FROM game');
    	$req->execute();
    	while($datas = $req->fetch()){
			$game = new Game();
			$game->hydrate($datas);
    		$games[] = $game;
    	}
    	$req->closeCursor();
    	return $games;
	}
}
?>