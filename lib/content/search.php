<?php
namespace lib\content;

use lib\content\Form;
use PDOStatement;
use PDO;
/**
 * @author Karl
 */
class Search {
    private $columns;
    private $adresseRaw;
    private $adressePage;
    private $adresseGets;
    
    public function __construct($adresse = '#'){
        $this->adresseRaw = $adresse;
        
        $adresseComp = explode('?', $adresse, 2);
        $this->adressePage = $adresseComp[0];
        $this->adresseArgs = (empty($adresseComp[1])?null:$adresseComp[1]);
        
        $this->adresseGets = array();
        if(!empty($this->adresseArgs)){
            $gets = explode('&amp;', $this->adresseArgs);
            foreach($gets as $get){
                $getData = explode('=', $get);
                $this->adresseGets[] = array('key' => $getData[0], 'value' => $getData[1]);
            }
        }
        
        $this->colums = array();
    }
    public function addColumn($name){
        $this->columns[] = $name;
    }
    public function getWhereLikes($needWhere = true){
        if(empty($_GET['search']) || count($this->columns) == 0)
            return null;
        $str = '';
        $first = true;
        foreach($this->columns as $column){
            if($first){
                if($needWhere)
                    $str .= ' WHERE';
                else
                    $str .= ' AND (';
                $first = false;
            }
            else
                $str .= 'OR';
            $str .= ' LOWER('.$column.') LIKE :search ';
        }
        if(!$needWhere)
            $str .= ') ';
        return $str;
    }
    public function bindSearch(PDOStatement &$req){
        if(!empty($_GET['search']) && count($this->columns) != 0)
            $req->bindValue('search', '%'.htmlspecialchars(strtolower($_GET['search'])).'%', PDO::PARAM_STR);
    }
    public function __toString(){
        $str = '<div class="search">';
        
        $form = new Form($this->adressePage, Form::METHOD_GET);
        $form->addSearch('search');
        foreach($this->adresseGets as $get){
            $form->addHidden($get['key'], $get['value']);
        }
        $form->addSubmit('Recheche');
        
        $str .= $form;
        $str .= '</div>';
        return $str;
    }
}

?>
