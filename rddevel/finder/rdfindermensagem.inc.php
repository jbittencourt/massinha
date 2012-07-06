<?


/**
 * Classe que define uma mensagem enviada atrav�s do Finder
 *
 * Essa classe apenas representa uma mensagem enviada atrav�s do F�rum
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access private
 * @version 0.5
 * @package rddevel
 * @subpackage finder
 * @see RDObj, RDFinder
 */

class RDFinderMensagem extends RDObj {

  /**
   * Construtor
   *
   * @param array $chaves Um array contendo os valores para codRemetente e codDestinatario
   */
  function RDFinderMensagem($chaves="") {

    $tabelaFinderMensagem = "Finder_Mensagens";
    $camposFinderMensagem = array("codMensagem",
				  "codRemetente",
				  "codDestinatario",
				  "tempo",
				  "desMensagem",
				  "flaLida");

    $chavesPFinderMensagem = "codMensagem";

    $this->RDObj($tabelaFinderMensagem,$camposFinderMensagem,$chavesPFinderMensagem);  
    $this->le($chave);
  }

  /**
   * Marca a mensagem como lida
   * 
   * Marca a mensagem atual como lida, assim o programa n�o avisar� mais o usu�rio sobre
   * a sua existencia
   */
  function marcaLida() {
    $this->flaLida=1;
    $this->salva();
  }
   

}

?>