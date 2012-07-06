<?
include("../../config.inc.php");
include_once("$pathuserlib/amchatsala.inc.php");
include_once("$rdpath/chat/rdchatmensagem.inc.php");

$pag = new RDPagina();

$pag->add("<form action=\"juliano.php\" method=post>");
$pag->add("<br>Codigo da Sala <input type=text name=frm_codsala>");
$pag->add("<br><input type=submit value=\"Envia\">");
$pag->add("</form>");


if(!empty($_REQUEST[frm_codsala])) {


  $chaves[] = opMVal("chat_mensagens","codRemetente","user","codUser");
  $chaves[] = opVal("codSalaChat",$_REQUEST[frm_codsala],"chat_mensagens");

  $param = new RDParam();
  $param->setCamposProjecao(array("codMensagem", "nomUser", "chat_mensagens.tempo", "desMensagem"));
  

  $lista = new RDLista("RDChatMensagem",$chaves,"chat_mensagens.tempo",$param);

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
      $pag->add("<br>".$user->nomUser.";".$user->nomPessoa.";".date("d/m/Y",$user->datNascimento));
    }
  }
  

  $pag->add("<br>Mensagens do chat<p>");

  if(!empty($lista->records)) {
    foreach($lista->records as $men) {
      $pag->add("<br>".$men->codMensagem.";".date("h:i:s",$men->tempo).";".$men->nomUser.";".$men->desMensagem);
    }
  }


}

$pag->imprime();

?>