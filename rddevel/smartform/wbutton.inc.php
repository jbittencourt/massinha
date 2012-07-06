<?

/**
 */
/**
 * Classe que implementa um Botao
 *
 * Classe que implementa um Botao
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WButton extends WInputEl {

  function WButton($name,$value,$type="submit") {
    $this->WInputEl($name,$value,$type);
  }

  function setOnClick($onClick) {
    $this->prop[onClick] = $onClick;
  }

  /** Seta a acao(onclick) deste botao como redirecionamento para a url desejado
   *
   */
  function setRedir($url) {
    $this->setOnClick("window.location.href = '".$url."'");
  }

}

?>