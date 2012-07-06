<?


include_once("$rdpath/finder/rdfindermensagem.inc.php");

//lista dos erros poss�veis no ambiente
$RD_Errors[] = "RD_FINDER_AMBIENTE_VAZIO";
$RD_Errors[] = "RD_FINDER_USER_VAZIO";

$RD_Errors[] = "RD_FINDER_MODOS_VAZIOS";



/**
 * Constantes que definem o modo de visibilidade do finder
 * @const RD_FINDER_MODO_NORMAL Visibilidade normal
 * @const RD_FINDER_MODO_OCUPADO Ocupado � visivel mas n�o pode receber mensagens
 * @const RD_FINDER_MODO_INVISIVEL Invisivel
 */
define("RD_FINDER_MODO_NORMAL",0);
define("RD_FINDER_MODO_OCUPADO",1);
define("RD_FINDER_MODO_INVISIVEL",2);

/**
 * Classe que implementa as funcionalidades da ferramenta Finder
 *
 * Classe que implementa as funcionalidades da ferramenta Finder
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage finder
 * @see  RDFinderMensagem, RDFerramenta
 */
class RDFinder extends RDFerramenta {
  /**
   * @var integer $modo Guarda o modo de opera��o atual do finder(normal, oculto, ocupado)
   * @var array $chats_abertos Guarda todos os chats que est�o atualmente abertos no sistema
   */
  var $modo, $chats_abertos;

  /**
   * Construtor da Classe Finder
   *
   * Esse construtor inicializa a ferramenta finder como uma ferramenta autentica do ambiente
   * pegando o seu ID como tal.
   */
  function RDFinder() {
    $this->RDFerramenta("RDFinder","Finder");
    $this->tempo = time();

  }


  function getModos() {
    
    $modes[RD_FINDER_MODO_NORMAL] = "Normal";
    $modes[RD_FINDER_MODO_INVISIVEL] = "Insiv&iacute;vel";
    $modes[RD_FINDER_MODO_OCUPADO] = "Ocupado";


    return $modes;

  }

  /**
   * Lista os usu�rios conectados
   *
   * Obt�m a partir da do objeto ambiente que deve estar registrado
   * na se��o quais s�o os usu�rios atualment conectados
   *
   * @return mixed  Retorna com uma RDLista dos usu�rios atualmente conetados ou um RDError
   * @see RDLista, RDUser, RDAmbiente
   */
  function getOnlineUsers() {
    
    if(empty($_SESSION[ambiente])) 
      return new RDError("RD_FINDER_AMBIENTE_VAZIO");

    return $_SESSION[ambiente]->getOnlineUsers();
  }



  function isChatOpen($codUser) {
    return !empty($_SESSION[finder_tmp][chats_abertos][$codUser][chating]);
  }

  /**
   * Envia um mensagem para um usu�rio conectado
   *
   * @param string $mensagem Mensagem a ser enviada
   * @param integer $para C�digo do usu�rio para quem se deseja enviar um mensagem
   */
  function enviaMensagem($para,$mensagem) {

    $eu = $_SESSION[usuario]->codUser; 

    $men = new RDFinderMensagem();
    $men->codRemetente = $eu;
    $men->codDestinatario = $para;
    $men->desMensagem = $mensagem;
    $men->tempo = time();
    $men->flaLida = 0;
    $men->salva();

  }

  function getNewMessages($para,$time) {
    
    $eu = $_SESSION[usuario]->codUser;

    $chaves[] = opVal("tempo",$time,"",">");
    $param = new RDParam();
    $param->setSqlWhere("(((codRemetente='$eu') OR (codDestinatario='$eu')) AND ((codRemetente='$para') OR (codDestinatario='$para')))");

    $lst = new RDLista("RDFinderMensagem",$chaves);

    return $lst;
  }


  /**
   * Muda o modo do usu�rio.
   *
   * O modo do usu�rio deve estar dentro do array $this->modos. Ele faz a alterac�o necess�ria
   * no campo visibilidade da tabela sessao_ambiente(RDSessaoAMbiente). A secao do usu�rio est� 
   * registrada em $_SESSION[ambiente].
   *
   * @Param string $mensagem Mensagem a ser enviada
   * @param integer $para C�digo do usu�rio para quem se deseja enviar um mensagem
   */
  function mudaModo($modo) {

    if(!empty($_SESSION[sessao])) {
     
      $_SESSION[sessao]->flaVisibilidade = $modo;
      $_SESSION[sessao]->salva();
      $this->modo = $modo;
    }
    else {
      return new RDError("RD_FINDER_AMBIENTE_VAZIO");
    };
      
    return 1;

  }


  function getNewRequests() {

    $chaves[] = opVal("codDestinatario",$_SESSION[usuario]->codUser);
    $chaves[] = opVal("flaLida","0");

    $lst = new RDLista("RDFinderMensagem",$chaves);    

    $ret = array();
    if(!empty($lst->records)) {
      foreach($lst->records as $men) {
	
	if(!RDFinder::isChatOpen($men->codRemetente)) {
	  $ret[] = $men;
	  if(empty($_SESSION[finder_tmp][chats_abertos][$men->codRemetente][tempo]) ||
	     ($_SESSION[finder_tmp][chats_abertos][$men->codRemetente][tempo]>$men->tempo)) {
	    $_SESSION[finder_tmp][chats_abertos][$men->codRemetente][tempo] = $men->tempo;
	  }
	 
	};
      };
    };

    return $ret;

  }


  function getTempo($para) {
    if(!empty($_SESSION[finder_tmp][chats_abertos][$para][tempo])) {
      return $_SESSION[finder_tmp][chats_abertos][$para][tempo];
    }
    
    return $this->tempo;
  }

  function startChat($para) {
    $_SESSION[finder_tmp][chats_abertos][$para][chating] = 1;;
  }

  function stopChat($para) {
    $_SESSION[finder_tmp][chats_abertos][$para][chating] = 0;;
  }

  function putMessageInTabuList($codmen) {
    $_SESSION[finder_tmp][tabu_list][$cod_men] = 1;
  }

  function isMessageInTabuList($codmen) {
    return $_SESSION[finder_tmp][tabu_list][$cod_men];
  }



};


?>