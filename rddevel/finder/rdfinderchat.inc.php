<?

/**
 */
/**
 * Classe que define um chat ocorrendo entre duas pessoas do finder
 *
 * Cada conversa iniciada entre duas pessoas atrav�s do finder d� inicio a uma esp�cie de janela de chat.
 * Tal janela � representada por esse objeto de forma conceitual(n�o gr�fica) para que n�o ocorram problemas
 * como por exemplo um usu�rio X estar conectado em duas m�quina diferentes, e com um chat aberto com uma destas
 * conversando com o Usu�rio Y. S� que por uma confus�o do sistema a mensagem � enviada para a sua segunda se��o
 * na outra m�quina. Para solucionar esse tipo de problema as mensagens s�o enviadas para um chat espec�fico em um
 * IP espec�fico.
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
   * @param string $chave Recebe o valor da chave prim�ria
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
   * Esta fun��o recebe como par�metro os dois usu�rios que devem iniciar um chat, e faz um registro no 
   * banco de dados de sua conversa. 
   *
   * @param integer $remetente O c�digo do usu�rio que est� iniciando o chat
   * @param integer $requisitado O c�digo do usu�rio que est� sendo chamado para o chat
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
   * Verifica se um usu�rio est� participando deste chat
   *
   * @param integer $user O c�digo do usu�rio que se deseja saber se faz parte deste chat
   * @return integer 1 se o usu�rio participa do chat
   *                 0 se n�o participa
   */
  function participa($user) {
    return (($this->codIniciador==$user) || ($this->codRequisitado==$user));
  }

}


?>