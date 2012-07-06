<?

include_once("$rdpath/smartform/winputel.inc.php");

/**
 * Classe que implementa um form HIDDEN
 *
 * Classe que implementa um form HIDEEN
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WHidden extends WInputEl {

  function WHidden($name,$value) {    
    $this->WInputEl($name,$value,"hidden");
    $this->setNoLabel();
  }

}

?>