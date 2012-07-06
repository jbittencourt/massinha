<?


include_once("$pathlib/chat_mensagem.inc.php");

/**
 * Classe que implementa uma sala de chat.
 *
 * Classe que implementa uma sala de chat.
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access private
 * @version 0.5
 * @package rddevel
 * @subpackage chat
 * @see RDObj, RDFinder
 */
class RDChatSala extends RDObj {

  /**
   * Construtor
   *
   * @param array $chaves Um array contendo os valores para codRemetente e codDestinatario
   */
  function RDChatSala($chave="") {

    $tabelaFinderMensagem = "chat_sala";
    $camposFinderMensagem = array("codSala",
                                  "nomSala",
                                  "flaPermanente",
                                  "tempo"
                                  );

    $chavesPFinderMensagem = "codSala";

    $this->RDObj($tabelaFinderMensagem,$camposFinderMensagem,$chavesPFinderMensagem);
    $this->le($chave);
  }

  function listaMensagensDesde($tempo) {
    $chave[] = opVal("codSalaChat",$this->codSala);
    $chave[] = opVal("tempo",$tempo,"",">");
    
    $mensagens  = new RDLista("RDChatMensagem",$chave); 
        
    return $mensagens;    
  }

}

?>