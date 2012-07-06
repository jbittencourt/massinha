<?

/**
 */


/**
 * Classe que implementa um form tipo FILE
 *
 * Classe que implementa um form tipo FILE
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WFile extends WInputEl {

  function WFile($nome) {
    $this->setName($nome);
    $this->setType("file");
  }

} 


?>