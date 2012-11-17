<?php
namespace lib\content;
/**
 * @author Karl
 */
class Form {
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';
    const METHOD_DEFAULT = self::METHOD_POST;
    const ACTION_DEFAULT = '#';
    const ENCTYPE_DATA = 'multipart/form-data';
    const ENCTYPE_DEFAULT = null;
    const FILE_AUDIO = 'audio/*';
    const FILE_VIDEO = 'video/*';
    const FILE_IMAGE = 'image/*';
    
    private $inputs;
    private $action;
    private $method;
    private $enctype;
    
    public function __construct($action = self::ACTION_DEFAULT, $method = self::METHOD_POST, $enctype = self::ENCTYPE_DEFAULT){
        $this->action = $action;
        $this->method = $method;
        $this->enctype = $enctype;
    }
    
    private function ancientValue($name){
        switch($this->method){
            case self::METHOD_POST:
                return (!empty($_POST[$name])?htmlspecialchars($_POST[$name]):null);
            case self::METHOD_GET:
                return (!empty($_GET[$name])?htmlspecialchars($_GET[$name]):null);
            default:
                return null;
        }
    }
    
    public function addFieldset($name){
        $input = array('type' => 'fieldset', 'name' => $name);
        $this->inputs[] = $input;
    }
    public function closeFieldset(){
        $input = array('type' => 'endfieldset');
        $this->inputs[] = $input;
    }
    
    private function addInput($type, $name = null, $label = null, $required = false, $value = null, $disabled = false, $datas = null){
        if(!empty($name) && $value === null)
            $value = $this->ancientValue($name);
        $input = array('type' => $type, 'label' => $label, 'name' => $name, 'required' => $required, 'disabled' => $disabled, 'value' => $value, 'datas' => $datas);
        $this->inputs[] = $input;
    }
    public function addColor($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('color', $name, $label, $required, $value, $disabled);
    }
    public function addDate($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('date', $name, $label, $required, $value, $disabled);
    }
    public function addDatetime($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('datetime', $name, $label, $required, $value, $disabled);
    }
    public function addDatetimeLocal($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('datetime-local', $name, $label, $required, $value, $disabled);
    }
    public function addMail($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('email', $name, $label, $required, $value, $disabled);
    }
    public function addNumber($name = null, $label = null, $min = null, $max = null, $step = null, $required = false, $value = null, $disabled = false){
        $this->addInput('number', $name, $label, $required, $value, $disabled, array('min' => $min, 'max' => $max, 'step' => $step));
    }
    public function addPassword($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('password', $name, $label, $required, $value, $disabled);
    }
    public function addRange($name = null, $label = null, $min = null, $max = null, $step = null, $required = false, $value = null, $disabled = false){
        $this->addInput('range', $name, $label, $required, $value, $disabled, array('min' => $min, 'max' => $max, 'step' => $step));
    }
    public function addSearch($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('search', $name, $label, $required, $value, $disabled);
    }
    public function addTel($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('tel', $name, $label, $required, $value, $disabled);
    }
    public function addText($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('text', $name, $label, $required, $value, $disabled);
    }
    public function addTime($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('text', $name, $label, $required, $value, $disabled);
    }
    public function addUrl($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('url', $name, $label, $required, $value, $disabled);
    }
    public function addWeek($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('week', $name, $label, $required, $value, $disabled);
    }
    
    public function addTextarea($name = null, $label = null, $required = false, $value = null, $disabled = false){
        $this->addInput('textarea', $name, $label, $required, $value, $disabled);
    }
    public function addSelect($name = null, $label = null, array $options = array(), $required = false, $value = null, $disabled = false){
        if(!empty($name) && $value === null)
            $value = $this->ancientValue($name);
        $input = array('type' => 'select', 'label' => $label, 'name' => $name, 'options' => $options, 'required' => $required, 'disabled' => $disabled, 'value' => $value);
        $this->inputs[] = $input;
    }
    public function addCheckbox($name = null, $label = null, $value = null, $checked = false, $disabled = false){
        $input = array('type' => 'checkbox', 'label' => $label, 'name' => $name, 'checked' => $checked, 'disabled' => $disabled, 'value' => $value);
        $this->inputs[] = $input;
    }
    public function addRadio($name = null, $label = null, $value = null, $checked = false, $disabled = false){
        $input = array('type' => 'radio', 'label' => $label, 'name' => $name, 'checked' => $checked, 'disabled' => $disabled, 'value' => $value);
        $this->inputs[] = $input;
    }
    public function addFile($name = null, $label = null, $accept = null, $required = false, $value = null, $disabled = false){
        $this->addInput('file', $name, $label, $required, $value, $disabled, array('accept' => $accept));
        $this->enctype = self::ENCTYPE_DATA;
    }
    public function addSubmit($value, $name = null){
        $this->addInput('submit', $name, null, false, $value);
    }
    public function addReset($value, $name = null){
        $this->addInput('reset', $name, null, false, $value);
    }
    public function addHidden($name, $value){
        $this->addInput('hidden', $name, null, false, $value);
    }
    
    private function formatName($name){
        return (!empty($name)?' id="'.$name.'" name="'.$name.'"':null);
    }
    private function formatValue($value){
        return (!empty($value)?' value="'.$value.'"':null);
    }
    private function formatDisabled($disabled){
        return ($disabled?' disabled="disabled"':null);
    }
    private function formatRequired($required){
        return ($required?' required="required"':null);
    }
    private function formatDatas($datas){
        if(is_array($datas)){
            $str = '';
            foreach($datas as $type => $value){
                if(!empty($value))
                    $str .= ' '.$type.'="'.$value.'"';
            }
            return $str;
        }
        return null;
    }
    private function formatLabel($name, $label){
        if(!empty($label))
            return '<label for="'.$name.'">'.$label.'</label>';
        return null;
    }
    private function formatInput($type, $name, $required, $value, $disabled, $datas){
        return '<input type="'.$type.'"'.$this->formatName($name).$this->formatRequired($required).$this->formatDisabled($disabled).$this->formatDatas($datas).$this->formatValue($value).' />';
    }
    public function __toString(){
        $str = '<form action="'.$this->action.'" method="'.$this->method.'"'.(!empty($this->enctype)?' enctype="'.$this->enctype.'"':'').'>';
        foreach($this->inputs as $content){
            switch($content['type']){
                case 'textarea':
                    $str .= '<div>'.$this->formatLabel($content['name'], $content['label']).'<textarea'.$this->formatName($content['name']).$this->formatRequired($content['required']).$this->formatDisabled($content['disabled']).'>'.$content['value'].'</textarea></div>';
                    break;
                case 'select':
                    $str .= '<div>'.$this->formatLabel($content['name'], $content['label']).'<select id="'.$content['name'].'" name="'.$content['name'].'"'.$this->formatRequired($content['required']).$this->formatDisabled($content['disabled']).'>';
                    $firstOptGroup = true;
                    $str .= '<option></option>';
                    foreach($content['options'] as $option){
                        if(empty($option['value'])){
                            if($firstOptGroup){
                                $str .= '<optgroup label="'.$option['name'].'">';
                                $firstOptGroup = false;
                            }
                            else{
                                $str .= '</optgroup><optgroup label="'.$option['name'].'">';
                            }
                        }
                        else{
                            $str .= '<option value="'.$option['value'].'"'.(($option['value'] == $content['value'])?' selected="selected"':null).'>'.$option['name'].'</option>';
                        }
                    }
                    if(!$firstOptGroup)
                        $str .= "</optgroup>";
                    $str .= '</select></div>';
                    break;
                case 'fieldset':
                    $str .= '<fieldset><legend>'.$content['name'].'</legend>';
                    break;
                case 'endfieldset':
                    $str .= '</fieldset>';
                    break;
                case 'checkbox':
                case 'radio':
                    $str .= '<div><input type="'.$content['type'].'"'.$this->formatName($content['name']).$this->formatValue($content['value']).($content['checked']?' checked="checked"':null).$this->formatDisabled($content['disabled']).' />'.$this->formatLabel($content['name'], $content['label']).'</div>';
                    break;
                default:
                    $str .= '<div>'.$this->formatLabel($content['name'], $content['label']).$this->formatInput($content['type'], $content['name'], $content['required'], $content['value'], $content['disabled'], $content['datas']).'</div>';
                    break;
            }
        }
        $str .= '</form>';
        return $str;
    }
}

?>
