<?php
namespace lib\content;
/**
 * @author Karl
 */
class Pages {
  const PAGES_LEFT = 2;
  const PAGES_RIGHT = 2;
  const ELEMENTS_DEFAULT = 25;
  
  private $adresse;
  private $adresseArgs;
  private $nbPages;
  private $displayedElements;
  private $currentPage;
  private $html;
  
  public function __construct($adresse, $totalElements, $displayedElements = self::ELEMENTS_DEFAULT){
    $adresseComp = explode('.php', $adresse, 2);
    $this->adresse = $adresseComp[0];
    $this->adresseArgs = (empty($adresseComp[1])?'':$adresseComp[1]);
    if(!empty($this->adresseArgs)){
        $gets = explode('?', $this->adresseArgs, 2);
        $this->adresseArgs = '?'.(!empty($_GET['search'])?'search='.$_GET['search'].'&amp;':null).$gets[1];
    }
    else{
        $this->adresseArgs = (!empty($_GET['search'])?'?search='.$_GET['search']:null);
    }
    
    $this->displayedElements = $displayedElements;
    $this->nbPages = ceil($totalElements / $displayedElements);
    if(empty($_GET['n']) || !is_numeric($_GET['n']) || $_GET['n'] <= 0 || $_GET['n'] > $this->nbPages){
        $this->currentPage = 1;
    }
    else{
        $this->currentPage = $_GET['n'];
    }
    $this->generateHtml();
  }
  public function getFirstElement(){
      return ($this->currentPage - 1) * $this->displayedElements;
  }
  public function getNumElement(){
      return $this->displayedElements;
  }
  public function getCurrentPage(){
      return $this->currentPage;
  }
  private function buildUrl($numPage){
      return $this->adresse.'-'.$numPage.'.php'.$this->adresseArgs;
  }
  private function generateHtml(){
    $firstPageLeft = $this->currentPage - self::PAGES_LEFT;
    $firstPageLeft = ($firstPageLeft > 0 ? $firstPageLeft : 1);
    $lastPageRight = $this->currentPage + self::PAGES_RIGHT;
    $lastPageRight = ($lastPageRight > $this->nbPages ? $this->nbPages : $lastPageRight);
    $html = '<div class="pages">';
    if($this->currentPage > self::PAGES_LEFT + 1){
      $html .= '<a href="'.$this->buildUrl(1).'">&laquo; Début</a>';
      $html .= '<span class="ellipsis">...</span>';
    }
    if($this->currentPage > 1){
      $html .= '<a href="'.$this->buildUrl($this->currentPage - 1).'">&laquo;</a>';
    }
    for($i = $firstPageLeft; $i <= $lastPageRight; $i++){
      if($i == $this->currentPage){
        $html .= '<span class="current">'.$i.'</span>';
      }
      else{
        $html .= '<a href="'.$this->buildUrl($i).'">'.$i.'</a>';
      }
    }
    if($this->currentPage < $this->nbPages){
      $html .= '<a href="'.$this->buildUrl($this->currentPage + 1).'">&raquo;</a>';
    }
    if($this->currentPage < $this->nbPages - self::PAGES_RIGHT - 1){
      $html .= '<span class="ellipsis">...</span>';
      $html .= '<a href="'.$this->buildUrl($this->nbPages).'">Fin &raquo;</a>';
    }
    $html .= '</div>';
    $this->html = $html;
  }
  public function __toString(){
    return $this->html;
  }
}

?>
