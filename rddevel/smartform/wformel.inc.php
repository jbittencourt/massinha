<?


define("WFORMEL_DESIGN_SIDE",0);
define("WFORMEL_DESIGN_STRING_DEFINED",1);
define("WFORMEL_DESIGN_SIDE_RIGTH",2);
//define("WFORMEL_DESIGN_LABEL",3);
define("WFORMEL_DESIGN_OVER",3);
//label a esquerda em uma coluna separada
define("WFORMEL_DESIGN_LEFT_TWO_COLS",4);

/**
 * Classe que implementa um Elemento de um formulario
 *
 * Classe que implementa um Elemento de um formulario
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WFormEl extends RDPagObj {
  var $prop = array();
  var $label;
  var $design;

  function WFormEl($name="",$value="") {
    $this->prop[name] = $name;
    $this->prop[id] = $name;
    $this->prop[value] = $value;

    $this->setName($name);
    $this->setValue($value);
  }

  function setName($name) {
    $this->nome = $name;
    $this->prop[name] = $name;
    $this->prop[id] = $name;
  }

  function getName() {
    return $this->nome;
  }

  function setValue($value) {
    $this->prop[value] = $value;
  }

  function addLabel($label) {
    $this->label.= $label;
  }

  function setLabel($label) {
    $this->label = $label;
  }

  function getLabel() {
    return $this->label;
  }

  function getValue() {
    return $this->prop[value];
  }

  function setStyleClass($classe) {
    $this->prop["class"] = $classe;
  }

  function setOnChange($onChange) {
    $this->prop[onChange] = $onChange;
  }

  function setProp($propName,$propValue) {
    $this->prop[$propName] = $propValue;
  }

}

?>