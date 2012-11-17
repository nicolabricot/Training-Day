<?php
namespace lib\content;
/**
 * @author Karl
 */
class Table {
    private $contents;
    private $headers;
    private $footers;
    private $caption;
    
    public function __construct(){
        $this->caption = '';
        $this->contents = array();
        $this->headers = array();
        $this->footers = array();
    }
    
    public function addContent(){
        $this->contents[] = func_get_args();
    }
    public function addHeader(){
        $this->headers[] = func_get_args();
    }
    public function addFooter(){
        $this->footers[] = func_get_args();
        
    }
    public function setCaption($str){
        $this->caption = $str;
    }
    
    private function formatLine(array $datas){
        $str = '<tr>';
        foreach($datas as $data){
            $str .= '<td>'.$data.'</td>';
        }
        $str .= '</tr>';
        return $str;
    }
    private function formatBloc($type, array $lines){
        $str = '<'.$type.'>';
        foreach($lines as $line){
            $str .= $this->formatLine($line);
        }
        $str .= '</'.$type.'>';
        return $str;
    }
    public function __toString(){
        $str = '<table><caption>'.$this->caption.'</caption>';
        if(!empty($this->headers))
            $str .= $this->formatBloc('thead', $this->headers);
        if(!empty($this->contents))
            $str .= $this->formatBloc('tbody', $this->contents);
        if(!empty($this->footers))
            $str .= $this->formatBloc('tfoot', $this->footers);
        
        $str .= '</table>';
        return $str;
    }
}
?>
