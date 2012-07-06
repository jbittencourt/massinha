<?

include("../../config.inc.php");

include_once("$rdpath/email/rdimapmail.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/aebox.inc.php");
include_once("$pathtemplates/aecorreio.inc.php");

$f_email = new RDImapMail();

$ui = new RDui("email", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);


$pag = new AECorreio();

if(!empty($_REQUEST[frm_Mailbox])) {
  $mailbox="frm_Mailbox=".$_REQUEST[frm_Mailbox];
  $f_email->setMailbox($_REQUEST[frm_Mailbox]);
};

//se o mailbox for sent ou trash, nao deixa responder
if(!($_REQUEST[frm_Mailbox]=="sent") || ($_REQUEST[frm_Mailbox]=="Trash")) {
  $itens[$lang[responder_email]] = "compose.php?acao=A_reply&frm_idMen=$_REQUEST[frm_idMen]&$mailox";
}
$itens[$lang[men_recebidas]] = "email.php";
//$itens[$lang[men_enviadas]] = $_SERVER[PHP_SELF]."?frm_Mailbox=sent";
$itens[$lang[enviar_men]] = "compose.php";

$pag->setSubMenu($itens,"comum");

$men = $f_email->getMensagem($_REQUEST[frm_idMen]);

$fg = "<font class=\"fontgray\">";
$s = "colspan=3";

$from = htmlentities($men->from_email);
$to = htmlentities($men->to);

$pag->add("<br><table border=0 cellspacing=0 cellpadding=0 width=\"100%\">");
$pag->add("<tr><td $s class=\"tdblue\">$fg<b>De:</b> $from</td>");
$pag->add("<tr><td $s class=\"tdblue\">$fg<b>Para:</b> $to</td>");
$pag->add("<tr><td $s class=\"tdblue\">$fg<b>Assunto:</b> $men->subject</td>");
$pag->add("<tr><td $s class=\"tdblue\">$fg<b>Data:</b> $men->date</td>");
$pag->add("<tr><td $s class=\"tdblue\"><img src=\"$ulgimagens/dot.gif\" height=10></td>");

$pag->add("<tr>");

if(!is_array($men->body)) {
  switch($men->type) {
  case "text/plain":
    $body = nl2br(htmlentities($men->body));
    break;
  case "text/html":
    $body = $men->body;
    break;
	
  };

};

$pag->add("<td class=\"tdblue\"><img src=\"$urlimagens/dot.gif\" width=10 height=50></td>");
$pag->add("<td width=100%>$body</td>");
$pag->add("<td class=\"tdblue\"><img src=\"$urlimagens/dot.gif\" width=10></td>");


$pag->add("<tr><td $s class=\"tdblue\"><img src=\"$urlimagens/dot.gif\" width=10 height=50></td>");

$pag->add("<br><br><br>");

$pag->imprime();

?>
