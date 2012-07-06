<?

include_once("$rdpath/smartform/winputel.inc.php");

/**
 * Classe que implementa um RadioButton
 *
 * Classe que implementa um RadioButton
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WRadio extends WInputEl {

  function WRadio($name,$value,$label="") {
    $this->WInputEl($name,$value,"radio");
    $this->addLabel($label);

    $this->labelAfter = 1;
  }

  function check() {
    $this->prop[checked]="";
  }



}



?>
