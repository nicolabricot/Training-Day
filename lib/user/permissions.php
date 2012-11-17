<?php
namespace lib\user;
/**
 * @author Karl
 */
class Permissions {
    private $permissions;
    
    public function __construct(){
    }
    
    /*
    public function getArray(){
        return $this->permissions;
    }
     */
    
    public function addRight($str){
        if($str != '' && !$this->hasRight($str)){
            $cursor =& $this->permissions;
            $groups = preg_split('/_/', $str, -1, PREG_SPLIT_NO_EMPTY);
            foreach($groups as $group){
                if($group == '*')
                    break;
                else
                    $cursor =& $cursor[$group];
            }
            $cursor = '*';
        }
    }
    public function hasRight($str){
        if($str != ''){
            $cursor =& $this->permissions;
            $groups = preg_split('/_/', $str, -1, PREG_SPLIT_NO_EMPTY);
            foreach($groups as $group){
                if(!empty($cursor)){
                    if($cursor == '*')
                        return true;
                    else if(array_key_exists($group, $cursor))
                        $cursor =& $cursor[$group];
                    else
                        return false;
                }
                else
                    return false;
            }
            return true;
        }
        return false;
    }
    
    public function unserialize($str){
        $this->permissions = unserialize($str);
    }
    public function serialize(){
        return serialize($this->permissions);
    }
}
?>