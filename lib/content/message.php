<?php
namespace lib\content;
use Exception;
/**
 * Description of Message
 *
 * @author Karl
 */
class Message {
    const ERROR = 'error';
    const WARNING = 'warning';
    const SUCCES = 'succes';
    
    private $type;
    private $message;
    
    public function __construct($message, $type = self::ERROR){
        switch($type){
            case self::ERROR:
            case self::WARNING:
            case self::SUCCES:
                break;
            default:
                throw new Exception('Invalid flag.');
        }
        $this->type = $type;
        $this->message = $message;
    }
    public function __toString(){
        switch($this->type){
            case self::ERROR:
                $title = 'Erreur';
                break;
            case self::WARNING:
                $title = 'Attention';
                break;
            case self::SUCCES:
                $title = 'Succès';
                break;
        }
        return '<div class="'.$this->type.'">'.$title.' : '.$this->message.'</div>';
    }
}

?>
