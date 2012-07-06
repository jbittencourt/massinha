<?

include_once("$rdpath/smartform/winputel.inc.php");


/**
 * Classe que implementa um form tipo Text
 *
 * Classe que implementa um form tipo Text
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WText extends WInputEl {

  function WText($name,$value="",$size="",$maxLength="") {
    $this->prop[size] = "";
    $this->prop[maxLength] = "";
    $this->WInputEl($name,$value,"text");  
    $this->setSize($size);
    $this->setMaxLength($maxLength);
  }

  function setPassword() {
    $this->prop[type] = "password";
  }

  function setSize($size) {
    $this->prop[size] = $size;
  }

  function setMaxLength($max) {
    $this->prop[maxLength] = $max;
  }

}



?>