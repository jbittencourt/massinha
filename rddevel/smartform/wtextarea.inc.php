<?

include_once("$rdpath/smartform/wformel.inc.php");

/**
 * Classe que implementa um form tipo Textarea
 *
 * Classe que implementa um form tipo Textarea
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WTextArea extends WFormEl {
  var $content;

  function WTextArea($name,$numRows,$numCols,$value="") {
    $this->setName($name);
    $this->setRows($numRows);
    $this->setCols($numCols);
    $this->add($value);
  }

  function setRows($numRows) {
    $this->prop[rows] = $numRows;
  }
  
  function setCols($numCols) {
    $this->prop[cols] = $numCols;
  }

  function setValue($value) {
    $this->content = $value;
  }

  function addContent($conteudo) {
    $this->content.= $conteudo;
  }

  function imprime() {

    if ($this->design == WFORMEL_DESIGN_LEFT_TWO_COLS) {
      $this->add($this->label);
      $this->add("</TD><TD>");
    }
    else {
      if($this->design != WFORMEL_DESIGN_STRING_DEFINED) $str = $this->label;
      if($this->design == WFORMEL_DESIGN_OVER) $str.= "<br>";
    }

    $str.= "<TEXTAREA ";
    foreach ($this->prop as $prop=>$valor) {
      $str.= $prop."=\"".$valor."\" ";
    }
    $str.= ">";
    $str.= $this->content;
    $str.= "</TEXTAREA>";
    $this->add($str);
    parent::imprime();
  }

}


?>