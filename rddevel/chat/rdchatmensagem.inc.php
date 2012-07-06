<?
/**
 */
/**
 * Classe que define uma mensagem enviada atrav?s do Finder
 *
 * Essa classe apenas representa uma mensagem enviada atrav?s do F?rum
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access private
 * @version 0.5
 * @package rddevel
 * @subpackage chat
 * @see RDObj, RDFinder
 */

class RDChatMensagem extends RDObj {

  /**
   * Construtor
   *
   * @param array $chaves Um array contendo os valores para codRemetente e codDestinatario
   */
  function RDChatMensagem($chaves="") {

    $tabelaFinderMensagem = "chat_mensagens";
    $camposFinderMensagem = array("codMensagem",
                                  "codSalaChat",
                                  "codRemetente",
                                  "codDestinatario",
                                  "tempo",
                                  "desMensagem",
				  "desTag"
                                  );
    $chavesPFinderMensagem = "codMensagem";

    $this->RDObj($tabelaFinderMensagem,$camposFinderMensagem,$chavesPFinderMensagem,$chaves);
    $this->le($chave);
  }

};
?>