<?

include("../../config.inc.php");
include_once("$pathuserlib/amarea.inc.php");
include_once("$pathuserlib/amprojeto.inc.php");
include_once("$pathtemplates/aeprojeto.inc.php");
include_once("$pathtemplates/aepagebox.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);


if(empty($_REQUEST[frm_codProjeto])) {
  die("Voce precisa definir um código para o projeto");
}


$pag = new AEProjeto();


$pag->add("<br><table border=0 cellpadding=0 cellspacing=0 width=\"100%\">");

$pag->add("<tr><td width=50% class=\"fontgray\"");
$pag->add(" valign=\"top\">");

if($_SESSION[usuario]->codUser == $proj->codOwner){
     $itens["&laquo;&nbsp;".$lang[voltar]] = "$urlferramentas/projetos/editarprojeto.php?frm_codProjeto=$_REQUEST[frm_codProjeto]";
}else{
     $itens["&laquo;&nbsp;".$lang[voltar]] = "$urlferramentas/projetos/projeto.php?frm_codProjeto=$_REQUEST[frm_codProjeto]";
}

$pag->setSubMenu($itens,"comum");

$tab = new AEPageBox(10);
$tab->SetTitle("img_tit_novidades_projetos.gif");
$tab->setLinkClass("projeto");
$proj = new AMProjeto($_REQUEST[frm_codProjeto]);
$lst = $proj->listaNovidades();

if(!empty($lst->records)){
  foreach($lst->records as $nov){
    $tempo = (date("j/n/Y",$nov->tempo));
    $text = "<div class=\"comum\">".$nov->desNovidade."</div><br>";
    $text .= "<div class=\"comum\" align=\"right\"><b>$tempo</b></div><br>";
    $tab->addItem($text);
  }
}else{
  $tab->add("<div class\"comum\">&nbsp;&nbsp;<b>$lang[erro_nov]</b></div><br>");
}

$pag->add($tab);

$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();


?>