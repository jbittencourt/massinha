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

if(!empty($_REQUEST[acao])) {
  switch($_REQUEST[acao]) {
  case "A_comentario_make":
    $proj = new AMProjeto($_REQUEST[frm_codProjeto]);
    $proj->addComentario($_REQUEST[frm_desComentario],$_SESSION[usuario]->nomPessoa);
    break;
  }
}


$pag = new AEProjeto();
$proj = new AMProjeto($_REQUEST[frm_codProjeto]);
if($_SESSION[usuario]->codUser == $proj->codOwner){
     $itens["&laquo;&nbsp;".$lang[voltar]] = "$urlferramentas/projetos/editarprojeto.php?frm_codProjeto=$_REQUEST[frm_codProjeto]";
}else{
     $itens["&laquo;&nbsp;".$lang[voltar]] = "$urlferramentas/projetos/projeto.php?frm_codProjeto=$_REQUEST[frm_codProjeto]";
}
$pag->setSubMenu($itens,"comum");


$pag->add("<br><table border=0 cellpadding=0 cellspacing=0 width=\"100%\">");

$pag->add("<tr><td width=50% class=\"fontgray\"");
$pag->add(" valign=\"top\">");

$empty_list = array("codComentario","tempo","codProjeto","codUser");
$form = new WSmartForm("AMComentarioProjeto","form_comenta",$_SERVER[PHP_SELF],$empty_list);

$form->setCancelOff();
$form->setStructure(2);
$form->setLabelClass("comum");
$form->setDesign(WFORMEL_DESIGN_OVER);
$form->spacing = 0;
$form->componentes[desNome]->prop[size]=20;
$form->forceToText("desComentario",50,600);
  
$form->addComponent("desComentario",new WText("frm_desComentario",'',25));
$form->addComponent("codProjeto",new WHidden("frm_codProjeto",$_REQUEST[frm_codProjeto]));
$form->addComponent("desNome",new WHidden("frm_desNome",$_SESSION[usuario]->nomPessoa));

$form->addComponent("acao",new WHidden("acao","A_comentario_make"));
$pag->add($form);


$tab = new AEPageBox(10);
$tab->SetTitle("img_tit_proj_comentario.jpg");
$tab->setLinkClass("projeto");
$lst = $proj->listaComentariosProjeto();

if(!empty($lst->records)){
  foreach($lst->records as $coment){
    $tab->addItem("<div class\"comum\"><b>".$coment->desNome."</b><br>&nbsp;&nbsp;".$coment->desComentario."</div><br>");
  }
}else{
  $tab->add("<div class\"comum\">&nbsp;&nbsp;<b>$lang[erro_com]</b></div><br>");
}

$pag->add($tab);

$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();


?>