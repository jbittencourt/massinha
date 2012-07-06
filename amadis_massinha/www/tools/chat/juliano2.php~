<?

include_once("$rdpath/interface/rdchattemplate.inc.php");
include_once("$pathuserlib/amuser.inc.php");

class AEChatArea extends RDChatTemplate {
  var $para,$emotions_tr;
  
  function AEChatArea($sala) {
    global $config_ini,$urlimagens;

    $finder = $config_ini[Chat];

    $this->campoDest = "codDestinatario";
    $this->campoSender = "codRemetente";
    $this->campoTempo = "tempo";

    $this->sala = &$sala;
    $this->CHAT_cod_user = $_SESSION[usuario]->codUser;
    $this->setSleepTime(4);

    $this->sala->enterRoom($_SESSION[usuario]->codUser);

    $em = $config_ini[Emotions];
    $images = $config_ini[Emotions_images];
                                                                                          
    $this->emotions_tr = array();
                                                                                          
    if (!empty($em)) {
      foreach($em as $name=>$sign) {
        $emotion = "$urlimagens/emotions/".$images[$name];
        $this->emotions_tr[$sign] = "<img src=\"$emotion\">";
      }
    }


  }

  function stopChat() {
    //recomessa a sessao que havia sido fechada e registra o final do chat.
    @session_start();
    $_SESSION[finder]->stopChat($this->para);
   
  }


  function drawMessage($men) {
    global $config_ini,$urlimagens,$lang;


    //faz a converçao dos smiles em imagens
    // array de emotions
    $conf = $config_ini[Chat];
                                                                                          
                                                                                          
    if($conf[activate_emotions]) {
      $temp = array();
      $men[desMensagem] = strtr($men[desMensagem],$this->emotions_tr);
    };


    $user = new AMUser($men[codRemetente]);
    if(empty($men[codDestinatario])) {
      $du = $lang[todos];
    }
    else {
      $dest  = new AMUser($men[codDestinatario]);
      $du = $dest->nomUser;
    };

    $hora = date("h:i",$men[tempo]);


    echo "<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"  bgcolor=\"$men[desTag]\">";
    echo "<tbody>";
    echo "<tr>";
    echo "<td width=\"100\" valign=\"top\" class=\"fontred2\"  style=\"padding-left: 10px;\">";
    echo "<b>".$user->nomUser."</b><br>$lang[fala] <i>". $du. "</i><br><font size=-1 class=\"comum\">$hora</font></td>";
    echo "<td valign=\"top\" class=\"fontgray\">$men[desMensagem]</td>";
    echo "</tr>";
    echo "</tbody>";
    echo "</table>";
    
    echo "<img src=\"$urlimagens/dot.gif\" heigth=10><br>";
    
    return (strlen($men[desMensage])/40);

  }

  function getNewMessages($time) {

    $mens = $this->sala->listaMensagensDesde($time);
    $user = $_SESSION[usuario];
    
    if(!empty($mens->records)) {
      foreach($mens->records as $men) {
	$ret[] = $men->toArray();
	unset($men);
      };
    };


    return $ret;
  }


};


?>
