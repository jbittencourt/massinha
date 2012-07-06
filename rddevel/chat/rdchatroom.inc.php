<?

include_once("$rdpath/chat/rdchatconnection.inc.php");

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
class RDChatRoom extends RDObj {
  /**
   * Construtor
   *
   * @param array $chaves Um array contendo os valores para codRemetente e codDestinatario
   */

  var $timeout=1800;

  function RDChatRoom($key="") {
    $this->pkFields = "codSala";
    $this->fgKFields = "";

    $fields_def = array(); 
    $fields_def[codSala] = array("type" => "bigint","size" => "20","bNull" => "0");
    $fields_def[nomSala] = array("type" => "varchar","size" => "30","bNull" => "0");
    $fields_def[desSala] = array("type" => "varchar","size" => "60","bNull" => "0");
    $fields_def[codPlataforma] = array("type" => "tinyint","size" => "4","bNull" => "0");
    $fields_def[flaPermanente] = array("type" => "char","size" => "1","bNull" => "0");
    $fields_def[datInicio] = array("type" => "bigint","size" => "20","bNull" => "0");
    $fields_def[datFim] = array("type" => "bigint","size" => "20","bNull" => "0");
    $fields_def[tempo] = array("type" => "bigint","size" => "20","bNull" => "0");
    $this->RDObj($this->getTables(),$this->getFields(),$this->pkFields,$key,$fields_def,$this->fgKFields);

  }

  function getTables() {
    return "chat_sala";
  }

  function getFields() {
    return array("codSala","nomSala","desSala","codPlataforma","flaPermanente","datInicio","datFim","tempo");
  }

  function setTimeOut($time) {
    $this->timeOut = $time;
  }

  function listaMensagensDesde($tempo=0,$codUser=0) {
    global $rdpath,$R_db;
    include_once("$rdpath/chat/rdchatmensagem.inc.php");

    $sql = "codSalaChat=".$this->codSala." AND tempo > ".$tempo." AND  (codDestinatario=0 OR codDestinatario=".$codUser.")";


      //$mensagens->records = $R_db->query($sql);
    
    
    //$chave[] = opVal("codSalaChat",$this->codSala);
    //$chave[] = opVal("tempo",$tempo,"",">");
    $param = new RDParam();
    $param->setSqlWhere($sql);
    $mensagens  = new RDLista("RDChatMensagem",$chave,'',$param); 
    
  
    return $mensagens;    
  }


  function sendMessage($to,$txt,$tag,$tempo="") {
      global $rdpath;
      include_once("$rdpath/chat/rdchatmensagem.inc.php");
      
      $men = new RDChatMensagem();
      $men->codSalaChat = $this->codSala;

      if(empty($tempo)) {
	$men->tempo = time();
      }
      else {
	$men->tempo = $tempo;
      };
      $men->codRemetente = $_SESSION[usuario]->codUser;
      $men->codDestinatario = $to;
      $men->desMensagem = $txt;
      $men->desTag = $tag;

      $men->salva();

      $this->datFim = time()+$this->timeOut;

      $this->salva();

  }

  function getConnectedUsers() {
    
    $keys[] = opVal("codSala",$this->codSala);
    $keys[] = opVal("flaOnline","1");
    $keys[] = opMVal(RDUser::getTables(),"codUser",RDChatConnection::getTables());

    $param = new RDParam();
    $tab = RDUser::getTables();
    $tabc = RDChatConnection::getTables();
    $param->setCamposProjecao(array("$tab.codUser","$tab.nomUser","$tab.nomPessoa"));
    $param->setDistinct();

    $list = new RDLista("RDUser",$keys,"$tab.nomPessoa",$param);
    return $list;

  }

  function enterRoom($codUser) {


    $keys[] = opVal("codUser",$codUser);
    $keys[] = opVal("codSala",$this->codSala);
    $keys[] = opVal("flaOnline","1");
    $new = new RDChatConnection($keys);

    if($new->novo) {
      $new = new RDChatConnection();
      $new->codSala = $this->codSala;
      $new->codUser = $codUser;
      $new->datEntrou = time();
      $new->flaOnline = 1;
      $new->salva();
      return $new->codConexao;
    }

    return 0;
  }


  function leaveRoom($codUser) {

    $keys[] = opVal("codUser",$codUser);
    $keys[] = opVal("codSala",$this->codSala);
    $keys[] = opVal("flaOnline","1");
    $new = new RDChatConnection($keys);

    if(!$new->novo) {
      $new->datSaiu = time();
      $new->flaOnline = 0;
      $new->salva();
    }

    return 1;
  }



}

?>