<?php
namespace lib\user;
use lib\db\DataBase;
/**
 * @author Karl
 */
class Computer {
    private $id = 0;
	private $type;
	private $gpu;
	private $cpu;
	private $ram;
	private $screen;
	private $drive;
	private $optique;

	public __construct(){
	}

	public hydrate(array $datas){
		foreach($datas as $key => $value){
            switch($key){
                case 'id':
                    $this->$key = (int) $value;
                    break;
                case 'type':
                case 'gpu':
                case 'cpu':
                case 'ram':
                case 'screen':
                case 'drive':
                case 'optique':
                    $this->$key = (string) $value;
                    break;
            }
        }
	}

	public function getType(){
		return $this->type;
	}
	public function setType($str){
		$this->type = (string) $str;
	}
	public function getGpu(){
		return $this->gpu;
	}
	public function setGpu($str){
		$this->gpu = (string) $str;
	}
	public function getCpu(){
		return $this->cpu;
	}
	public function setCpu($str){
		$this->cpu = (string) $str;
	}
	public function getRam(){
		return $this->ram;
	}
	public function setRam($str){
		$this->ram = (string) $str;
	}
	public function getScreen(){
		return $this->screen;
	}
	public function setScreen($str){
		$this->screen = (string) $str;
	}
	public function getDrive(){
		return $this->drive;
	}
	public function setDrive($str){
		$this->drive = (string) $str;
	}
	public function getOptique(){
		return $this->optique;
	}
	public function setOptique($str){
		$this->optique = (string) $str;
	}
}
?>