<?

$sem_login=1;
include("config.inc.php");
#include_once("$pathtemplates/aemain.inc.php");
include_once("$pathtemplates/aelogin.inc.php");

$ui = new RDui("login");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag  = new RDPagina();
$pag->requires("$urlcss/amadis_escola.css","CSS");


$pag->add("<table cellpadding=\"0\" cellspacing=\"0\" align=\"center\" border=\"0\"><br><br><br>");

$pag->add("<tr><td align=\"center\"><a href=\"#\" class=\"comum\">".$lang[esqueci]."</a> - ");
$pag->add("<a href=\"$url/cadastro.php\" class=\"comum\">".$lang[cadastrar]."</a> - <a href=\"#\" class=\"comum\"");
$pag->add(">".$lang[info]."</a></td></tr></table><br><br><br>");


$pag->add(new AELogin());

if($_REQUEST[frm_error]){
  $pag->add("<div align=\"center\">". $lang[erro]."</div>");
}

$pag->imprime();


?>