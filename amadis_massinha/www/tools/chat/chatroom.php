<?

include("../../config.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathuserlib/amchatsala.inc.php");

$ui = new RDui("chat", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

if(empty($_REQUEST[frm_codSala])) {
  die("oops, no cod for sala");
}
else {
  $sala = new AMChatSala($_REQUEST[frm_codSala]);
  if($sala->datFim<(time()-$config_ini[Chat][room_timeout])) {
    $sala_fechada = 1;
    $_REQUEST[acao]="A_chat";
  }
}


if(empty($_SESSION[AMADIS_ESCOLA][CHAT][COLOR])) {
   list($usec, $sec) = explode(' ', microtime());
   mt_srand((int) $sec + ((int) $usec * 10000));

   
   $cor[] = "#e4ede2";
   $cor[] = "#e4ede2";
   $cor[] = "#e4ede2";
   $cor[] = "#DFDFFF";
   $cor[] = "#B8EBAC";

   $index =  mt_rand(0,count($cor));                      
   

   $_SESSION[AMADIS_ESCOLA][CHAT][COLOR] = $cor[$index];


}



if(isset($_REQUEST[acao])) {
  switch($_REQUEST[acao]) {
  case "A_chat":
    include_once("$pathtemplates/aechatarea.inc.php");
    $chat = new AEChatArea($sala);
    $chat->onlyShow = $sala_fechada;
    $tempo=0;
    if($sala_fechada) 
      $tempo = $sala->datInicio-1;
    $chat->mainLoop($tempo);
    $sala->sendMessage(0,$_SESSION[usuario]->nomUser." ".$lang[saiu_sala],$_SESSION[AMADIS_ESCOLA][CHAT][COLOR]);
    $sala->leaveRoom($_SESSION[usuario]->codUser);
    die();
    break;
  
  case "A_send_make":
    $sala->sendMessage($_REQUEST[frm_para],$_REQUEST[frm_mensagem],$_SESSION[AMADIS_ESCOLA][CHAT][COLOR]);
  case "A_send":

    $pag = new RDPagina();
    $pag->setMargin(0,0,0,0);
    $pag->add("<form name=envia method=post action=\"$_SERVER[PHP_SELF]?acao=A_send_make\">");
    $pag->add("<input type=hidden name=frm_codSala value=\"$sala->codSala\">");
    $pag->add("<table width=\"550\" border=0 cellspacing=0 cellpadding=0  height=64 background=\"$urlimagens/bg_tela_chat.gif\">");
    $pag->add("<tbody><tr><td width=160 style=\"padding-left: 15px; padding-top: 5px;\"");
    $pag->add("class=\"fontwhite\"><b>Enviar para</b></td>");
    $pag->add("<td rowspan=2 width=1 bgcolor=\"#ffffff\"><img src=\"$urlimagens/dot.gif\" width=1 height=1></td>");
    $pag->add("<td style=\"padding-left: 15px; padding-top: 5px;\" class=\"fontwhite\"><b>Mensagem</b></td>");
    $pag->add("</tr><tr><td style=\"padding-left: 15px;\">");
    $pag->add("<select name=\"frm_para\">");

    $pag->add("<option value=0>Todos</option>");

    $online = $sala->getConnectedUsers();
    if(!empty($online->records)) {
      foreach($online->records as $user) {
	$pag->add("<option value=\"$user->codUser\"> $user->nomUser");
      }
    }

    $pag->add("</select>");
    $pag->add("</td><td style=\"padding-left: 15px;\">");
    $pag->add("<input type=text name=\"frm_mensagem\"><input type=submit name=submit value=\"Enviar\">");
    $pag->add("</td></tr></tbody></table>");
    $pag->add("<input type=hidden name=frm_scroll value=1>");
    $pag->add("</form>");
    $pag->imprime();
    die();
  default:
    die("oops");
  }
}


$sala->sendMessage(0,$_SESSION[usuario]->nomUser." ".$lang[entra_sala],$_SESSION[AMADIS_ESCOLA][CHAT][COLOR],time()+10);

echo "<frameset rows=\"*,80\" frameborder=\"NO\" border=\"0\" framespacing=\"0\" cols=\"*\"> ";
echo "<frame name=\"cima\" scrolling=\"auto\" noresize src=\"$_SERVER[PHP_SELF]?acao=A_chat&frm_codSala=".$sala->codSala."\" >";
echo "<frame name=\"finder_envia\" src=\"$_SERVER[PHP_SELF]?acao=A_send&frm_codSala=".$sala->codSala."\">";
echo "</frameset>";


?>
