<?php
namespace lib\content;
/**
 * @author Karl
 */
class Menu {
    private $cases;
    
    public function __construct(){
        
    }
    
    public function addLink($name, $url, $current = false){
        $this->cases[] = array('name' => $name, 'url' => $url, 'current' => $current);
    }
    
    public function __toString(){
        $str = '';
        if(is_array($this->cases)){
            $str .= '<ul>';
            foreach($this->cases as $case){
                $str .= '<li'.($case['current']?' class="current"':null).'><a href="'.$case['url'].'">'.$case['name'].'</a></li>';
            }
            $str .= '</ul>';
        }
        return $str;
    }
}

?>
