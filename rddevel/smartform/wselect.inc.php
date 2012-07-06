<?

include_once("$rdpath/smartform/winputel.inc.php");


/**
 * Classe que implementa uma lista ou combobox
 *
 * Classe que implementa uma lista ou combobox
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */

class WSelect extends WFormEl {
  var $options=array();

  function WSelect($name) {
    $this->setName($name);
  }

  function addOption($value,$text) {
    $this->options[$value] = $text;
  }

  function setMultiple() {
    $this->prop[multiple] = "";
  }

  function setSize($size) {
    $this->setMultiple();
    $this->prop[size] = $size;  
  }

  function parseOptionsFromList($list,$fieldValue,$fieldLabel) {
    if (!empty($list->records)) {
      foreach ($list->records as $obj) {
	//$ob_array = $obj->toArray();
	//$this->addOption($ob_array[$fieldValue],$ob_array[$fieldLabel]);
	$this->addOption($obj->$fieldValue,$obj->$fieldLabel);
      }
    }
  }

  function parseOptions($list,$fieldValue="",$fieldLabel="") {

    if(empty($list)) return;
    //if(is_subclass_of($list,"rdlista") || (get_class($list)=="rdlista")) {
    if(is_a($list,"rdcursor")) {
      $this->parseOptionsFromList($list,$fieldValue,$fieldLabel);
    }
    else {
       if(is_array($list)) {
	foreach($list as $key=>$item) {
	  $this->addOption($key,$item);
	}
      }
    }

  }

  function imprime() {   

    if ($this->design == WFORMEL_DESIGN_LEFT_TWO_COLS) {
      $str = $this->label."</TD><TD>";
    }
    else {
      if($this->design != WFORMEL_DESIGN_STRING_DEFINED) $str = $this->label;
      if($this->design==WFORMEL_DESIGN_OVER) $str .= "<br>";
    }
    $str.= "<SELECT ";
    foreach ($this->prop as $prop=>$valor) {
      if(empty($valor)) {
	$str.= $prop." ";
      }
      else {
	$str.= $prop." =\"".$valor."\" ";
      };
    }
    $str.= ">";
    $this->add($str);

    if(count($this->options)>0) {
      foreach ($this->options as $value=>$text) {
	$chk = "";
	if($this->prop[value] == $value ) $chk = "SELECTED";
	$this->add("<OPTION value=\"".$value."\" $chk>".$text."</OPTION>");
      }
    }
    $this->add("</SELECT>");
    parent::imprime();

  }

}


?>
