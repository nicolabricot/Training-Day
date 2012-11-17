<?php
namespace lib\lan;
use lib\db\DataBase;
use PDO;
/**
 * @author Karl
 */
class Schedule {
	private $start;
	private $end;

	public function __construct($timeStart, $timeStop){
		$this->start = $timeStart;
		$this->end = $timeStop;
	}

	public function getStart(){
		return $this->start;
	}
	public function setStart($time){
		$this->start = $time;
	}
	public function getEnd(){
		return $this->end;
	}
	public function setEnd($time){
		$this->end = $time;
	}
	public function isIn($time){
		return ($time <= $this->end && $time >= $this->start);
	}

	static public function saveSchedule($tournament, Schedule $schedule){
		$req = DataBase::getInstance()->prepare('INSERT INTO tournament_schedule (tournament, start, stop) VALUES (:id, :start, :stop)');
    	$req->bindvalue('id', $tournament, PDO::PARAM_INT);
    	$req->bindvalue('start', $schedule->getStart(), PDO::PARAM_INT);
    	$req->bindvalue('stop', $schedule->getEnd(), PDO::PARAM_INT);
    	$req->execute();
    	$req->closeCursor();
	}
	static public function deleteSchedules($tournament){
		$req = DataBase::getInstance()->prepare('DELETE FROM tournament_schedule WHERE tournament = :id');
    	$req->bindvalue('id', $tournament, PDO::PARAM_INT);
		$req->execute();
    	$req->closeCursor();
	}
}
?>