<?


/**
 */

/**
 * Classe que implementa um checkbox
 *
 * Classe que implementa um checkbox
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */

class WCheckBox extends WInputEl {

  function WCheckBox($name,$value,$label="") {
    $this->WInputEl($name,$value,"checkbox");
    $this->addLabel($label);    
  }

  function check() {
    $this->prop[checked] = 1;
  }

  function uncheck() {
    unset($this->prop[checked]);
  }

  function imprime() {
    $this->design = WFORMEL_DESIGN_SIDE_RIGTH;
    parent::imprime();
  }
}


?>