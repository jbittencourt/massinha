<?
/**
 */
/**
 * Classe que representa as unidades de interface dentro do programa
 *
 * O conceito de unidades de interface é muito presente em campos como
 * o de IHC(Interaca Humano-Computador). Ele refere-se a cada unidade
 * de apresentacao mais ou menos independente que existe dentro de um
 * ambiente. Por exemplo dentro de um arquvi php que utiliza a metodologia
 * de "acoes" podem existir várias unidades de interface. Ela também guarda
 * informacoes importantes como o arquivo do qual essa unidade foi referanciada.
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 */
class RDUI {
  var $nome,$arquivo,$urlUIAnterior;
  var $groupName;

  /** Funcao construtora que inicializa o objeto
   *
   * Por default essa funcao já seta automaticamente as variaveis $urlUIAnterior e $arquivo
   *
   * @param string $nome_ui Definicao geral do nome de uma unidade de interface, por exemplo "escolas"
   * @param string $acao Diz dentro dessa unidade qual a acao que esta atualmente sendo realizada
   * @param string $arquivo Arquivo da onde atualmente está sendo referindo-se a UI. Normalmente $PHP_SELF
   */
  function RDUI($nome_ui="",$acao="",$arquivo="") {
    
    $this->groupName = $nome_ui;

    if (!empty($acao)) {
      if (strpos($acao,"make") ==0 ) {	
	$acao = substr($acao,strpos($acao,"A_")+2);
	$nome_ui = $nome_ui. "_". $acao;
      }
    }
 
    $this->nome    = $nome_ui;

    //seta a url de onde chegou-se a esta pagina
    $this->setUrlUIAnterior($_SERVER[HTTP_REFERER]);
    //seta o arquivo desta UI
    $this->setArquivo($_SERVER[PHP_SELF]);

  }

  /** Funcao que seta a url de onde chegou-se a esta UI. Para ser utilizado com acoes do tipo voltar e cancelar.
   *
   * @param string $url Url anterior
   */
  function setUrlUIAnterior($url) {
    $this->urlUIAnterior = $url;
  }

  /** Funcao que altera o nome do arquivo
   *
   * @param string $arquivo Arquivo da onde atualmente está sendo referindo-se a UI. Normalmente $PHP_SELF
   */
  function setArquivo($arq) {
    $this->arquivo = $arq;
  }


  /** Retorna o nome do grupo da UI
   *
   * O nome de uma unidade de interface e sempre composto por nome+acao. O nome normalmente é uma
   * invariante dentro de um grupo maior de acoes que podem serem executadas. De certa forma pode-se
   * dizer que o nome e a acao formam uma arvore. No entanto nos arquivos de linguagem, essa hierarquia
   * não pode ser expressa por trata-se de um arquivo tipo ini. Dentro de RDAmbiente existe uma funcao que
   * remonta essa hierarquia mas precisa do nome geral da class
   *
   * @see RDAmbiente
   */
  function getGroupName() {
    return $this->groupName;
  }
 
}




?>