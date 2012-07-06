<?
$sem_login=1;
include("../../config.inc.php");
include_once("$pathtemplates/amtprojeto.inc.php");
include_once("$pathtemplates/ambox.inc.php");
include_once("$pathtemplates/ampagebox.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AMTProjeto();

$itens["&laquo;&nbsp;".$lang[voltar]] = "$url/index.php";
$itens["&laquo;&nbsp;".$lang[ir_projeto]] = "$urlferramentas/projetos/projeto.php?frm_codProjeto=$_REQUEST[frm_codProjeto]";

$pag->setSubMenu($itens,"comum");

$pag->add("<br><table border=0 cellpadding=0 cellspacing=0>");

//coluna da esquerda
$pag->add("<tr><td class=\"comum\"");
$pag->add(" valign=\"top\">");
$tab = new AMBox();
$anuncio = $_SESSION[ambiente]->listaAnuncios($_REQUEST[frm_codAnuncio]);
foreach($anuncio->records as $item){
  $a = "<a class=\"comum\" href=\"$urlferramentas/projetos/projeto.php?frm_codProjeto=$item->codProjeto\">$item->desTitulo</a>";
  $div = "<div class=fontgray>";
  $dvif = "</div>";
  $tab->add("<div class=fonttit1>$item->desTituloAnuncio</div>");
  $tab->add("$div <br>$item->desAnuncio $divf");
  $tab->add("$div <br>$lang[proj]:$divf $a");
}

$pag->add($tab);

$pag->add("</td></tr></table>");

$pag->imprime();


?>