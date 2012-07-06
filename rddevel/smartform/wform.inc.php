<?


include_once("$rdpath/smartform/wtext.inc.php");
include_once("$rdpath/smartform/wtextarea.inc.php");
include_once("$rdpath/smartform/wselect.inc.php");
include_once("$rdpath/smartform/whidden.inc.php");
include_once("$rdpath/smartform/wbutton.inc.php");
include_once("$rdpath/smartform/wfile.inc.php");
include_once("$rdpath/smartform/wradiogroup.inc.php");
include_once("$rdpath/smartform/wcheckbox.inc.php");
include_once("$rdpath/smartform/wformelgroup.inc.php");
include_once("$rdpath/smartform/wlistadd.inc.php");
include_once("$rdpath/smartform/wdata.inc.php");
//include_once("$rdpath/smartform/wcheckboxgroup.inc.php");

/**

Algumas constantes usadas na escolha do tamanho do  widget para o campo
*/

define("WTEXT_SIZE",60);
define("WTEXTAREA_ROWS",10);
define("WTEXTAREA_COLS",40);
define("WFLOATSIZE",15);

/**
 * Classe que implemente um formulário
 * @author Maicon Brauwers <maicon@edu.ufrgs.br>
 * @access public
 * @abstract
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see RDObj
 */

class WForm extends RDPagObj {
  var $name,$action,$method,$enctype;
  
  function WForm($name,$action,$method="",$enctype="") {
    $this->setName($name);
    $this->setAction($action);
    if (empty($method)) {
      $method = "POST";
    }      
    $this->setMethod($method);
    $this->setEnctype($enctype);    
  }

  function setAction($action) {
    $this->action = $action;
  }

  function setMethod($method) {
    $this->method = $method;
  }

  function setEnctype($enctype="") {
    $this->enctype = $enctype;
  }


  function setName($name) {
    $this->name = $name;
  }

  /** Constroi campos hidden para cada par/valor em $_REQUEST
   *
   */
  function buildHiddenFromRequest() {
    if (count($_REQUEST) > 0) {
      foreach($_REQUEST as $nomeCampo=>$valor) {
	$wHidden = new WHidden($nomeCampo,$valor);
      }
    }
  }

  function imprime() {
    global $smartform;

    ob_start();
    parent::imprime();
    $data = ob_get_contents();
    ob_end_clean();
    

    if(!empty($smartform[$this->name][submit_actions])) {
      $subAction = "onSubmit=\"";
      foreach($smartform[$this->name][submit_actions] as $action) {
	$action = strtr($action,array("\formName"=>$this->name));
	$subAction.= "$action;";
      }
      $subAction.= "\""; 
    }

    $str = "<FORM name=\"".$this->name."\" action=\"".$this->action."\" method=\"".$this->method."\" $subAction ";
    if (!empty($this->enctype)) {
      $str.= "enctype=\"".$this->enctype."\" ";
    }
    $str.= ">";
    echo $str;
    echo $data;
    echo "</FORM>";
  }


}



?>