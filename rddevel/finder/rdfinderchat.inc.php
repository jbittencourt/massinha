<?

/**
 */
/**
 * Classe que define um chat ocorrendo entre duas pessoas do finder
 *
 * Cada conversa iniciada entre duas pessoas através do finder dá inicio a uma espécie de janela de chat.
 * Tal janela é representada por esse objeto de forma conceitual(não gráfica) para que não ocorram problemas
 * como por exemplo um usuário X estar conectado em duas máquina diferentes, e com um chat aberto com uma destas
 * conversando com o Usuário Y. Só que por uma confusão do sistema a mensagem é enviada para a sua segunda seção
 * na outra máquina. Para solucionar esse tipo de problema as mensagens são enviadas para um chat específico em um
 * IP específico.
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access private
 * @version 0.5
 * @package rddevel
 * @subpackage finder
 * @see RDObj, RDFinder
 */

class RDFinderChat extends RDObj {
  var $chating;

  /**
   * Construtor
   *
   * @param string $chave Recebe o valor da chave primária
   */
  function RDFinderChat($chave="") {

    $tabela = "Finder_Chat";
    $campos = array("codFinderChat",
		    "codIniciador",
		    "codRequisitado",
		    "datInicio",
		    "datFim");
    $chaveP = "codFinderChat";

    $this->RDObj($tabela,$campos,$chavesP);  
    $this->le($chave);

    $chating = 0;

  }



  /**
   * Inicializador do Chat
   *
   * Esta função recebe como parâmetro os dois usuários que devem iniciar um chat, e faz um registro no 
   * banco de dados de sua conversa. 
   *
   * @param integer $remetente O código do usuário que está iniciando o chat
   * @param integer $requisitado O código do usuário que está sendo chamado para o chat
   * @return integer Retorna o codigo do chat aberto
   */
  function inicia($remetente,$requisitado) {
    $this->codIniciador = $remetente;
    $this->codRequisitado = $requisitado;
    $this->datInicio = time();
    $this->salva();

    return $this->codFinderChat;
  }

  /**
   * Verifica se um usuário está participando deste chat
   *
   * @param integer $user O código do usuário que se deseja saber se faz parte deste chat
   * @return integer 1 se o usuário participa do chat
   *                 0 se não participa
   */
  function participa($user) {
    return (($this->codIniciador==$user) || ($this->codRequisitado==$user));
  }

}


?>