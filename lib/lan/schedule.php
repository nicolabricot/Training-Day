<?php
namespace lib\user;
/**
 * @author Karl
 */
class Team {
	private $start;
	private $end;

	public function __construct($timeStart, $timeStop){
		$this->start = $timeStart;
		$this->stop = $timeStop;
	}

	public function getStart(){
		return $this->start;
	}
	public function set($time){
		$this->start = $time;
	}
	public function getEnd(){
		return $this->end;
	}
	public function set($time){
		$this->end = $time;
	}
	public function isIn($time){
		return ($time <= $this->end && $time >= $this->start);
	}
}
?>