<?
include_once("../../config.inc.php");
include_once("$pathuserlib/amuser.inc.php");
include_once("$rdpath/chat/rdchatmensagem.inc.php");


class AEChatArea {
  var $para,$emotions_tr;
  
  function AEChatArea($sala) {
    global $config_ini,$urlimagens;

    $finder = $config_ini[Chat];

    $this->campoDest = "codDestinatario";
    $this->campoSender = "codRemetente";
    $this->campoTempo = "tempo";

    $this->sala = &$sala;
    $this->CHAT_cod_user = $_SESSION[usuario]->codUser;
    //    $this->setSleepTime(4);

    //$this->sala->enterRoom($_SESSION[usuario]->codUser);

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
      $du = "Todos";
    }
    else {
      $dest  = new AMUser($men[codDestinatario]);
      $du = $dest->nomUser;
    };

    $hora = date("h:i",$men[tempo]);



    $ret = "<tr>";
    $ret.="<td width=\"100\" valign=\"top\" class=\"fontred2\"  style=\"padding-left: 10px;\">";
    $ret.="<b>".$user->nomUser."</b><br>fala para <i>". $du. "</i><br><font size=-1 class=\"comum\">$hora</font></td>";
    $ret.="<td valign=\"top\" class=\"fontgray\">$men[desMensagem]</td>";
    $ret.="</tr>";
    $ret.="</tbody>";
    
    
    return $ret; //(strlen($men[desMensage])/40);

  }



};


$pag = new RDPagina();

$pag->add("<form action=\"juliano2.php\" method=post>");
$pag->add("<br>Codigo da Sala <input type=text name=frm_codsala>");
$pag->add("<br><input type=submit value=\"Envia\">");
$pag->add("</form>");


if(!empty($_REQUEST[frm_codsala])) {

    $chaves[] = opMVal("chat_mensagens","codRemetente","user","codUser");
  $chaves[] = opVal("codSalaChat",$_REQUEST[frm_codsala],"chat_mensagens");

  $param = new RDParam();
  $param->setCamposProjecao(array("codMensagem", "nomUser", "chat_mensagens.tempo", "desMensagem"));
  

  $lista = new RDLista("RDChatMensagem",$chaves,"chat_mensagens.tempo");

  $chaves = array();
  $chaves[] = opMVal("user","codUser","chat_mensagens","codRemetente");
  $chaves[] = opVal("codSalaChat",$_REQUEST[frm_codsala],"chat_mensagens");

  $param = new RDParam();
  $param->setCamposProjecao(array("nomUser", "nomPessoa", "datNascimento"));
  $param->setDistinct();
  

  $users = new RDLista(array("AMUser","RDChatMensagem"),$chaves,"nomUser",$param);

  $pag->add("<br>Usuarios que participaram do chat<p>");

  if(!empty($users->records)) {
    foreach($users->records as $user) {
      $pag->add("<br>Nome: ".$user->nomPessoa);
      $pag->add("<br>Apelido: ".$user->nomUser);
      $pag->add("<br>Data Nascimento: ".date("d/m/Y",$user->datNascimento));
      $pag->add("<P>");
    }
  }

  $pag->add("<p><br>Mensagens do chat<p>");

  $area = new AEChatArea($_REQUEST[frm_codsala]);
  $pag->add("<TABLE border=0 WIDTH=80%>");
  if(!empty($lista->records)) {
    foreach($lista->records as $men) {
      $tmp = $men->toArray();
      $pag->add($area->drawMessage($tmp));
    }
  }

  $pag->add("</table>");

}

$pag->imprime();


?>
