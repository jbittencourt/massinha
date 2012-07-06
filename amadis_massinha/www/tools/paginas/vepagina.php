<?

include("../../config.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/aepaginas.inc.php");
include_once("$pathtemplates/aebox.inc.php");
include_once("$pathuserlib/amprojeto.inc.php");

$ui = new RDui("paginas", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);


if(!empty($_REQUEST[frm_codUser])) {
  $iframe_url = "$urlpaginas/user_".$_REQUEST[frm_codUser];
}
else {
  if(!empty($_REQUEST[frm_codProjeto])) {
    $iframe_url = "$urlpaginas/project_".$_REQUEST[frm_codProjeto];
    $proj = new AMProjeto($_REQUEST[frm_codProjeto]);
  }
}

$pag = new AEPaginas();
$pag->navmenu->locked = 0;
$pag->slidein->setMode(SLIDEINMENU_MODE_CLOSED);
$pag->setLeftMargin(25);


$empty_list = array("codComentario","tempo","codProjeto","codProjeto","codUser");
if(!empty($_SESSION[usuario])) {
  $empty_list[] = "desNome";
};

if(!empty($proj)) {
//faz um hit no contador de p�ginas
  $proj->hits += 1;
  $proj->salva();
  
  $form = new WSmartForm("AMComentarioProjeto","form_comenta",$_SERVER[PHP_SELF]."?acao=A_comentario_make",$empty_list);
  $form->setStructure(4);
  $form->setCancelOff();
  $form->setLabelClass("comum");

  $form->setDesign(WFORMEL_DESIGN_SIDE);
  $form->spacing = 0;
  
  $form->componentes[desNome]->prop[size]=20;
  $form->forceToText("desComentario",50,600);
  $form->addComponent("codProjeto",new WHidden("frm_codProjeto",$_REQUEST[frm_codProjeto]));

  $pag->add("<div align=center valign=\"middle\">");
  $pag->add($form);
  $pag->add("</div>");

}  

$pag->add("<iframe onload=\"resizeFrame()\" id=\"id_if_pagina\" name=if_pagina src=\"$iframe_url\" width=\"800\" height=\"500\"></iframe>");

$js.= "var ns4=document.layers?1:0;";
$js.= "var ie4=document.all&&navigator.userAgent.indexOf(\"Opera\")==-1;";
$js.= "var ns6=document.getElementById&&!document.all?1:0;";
$js.= "function resizeFrame() {";
$js.= "   var width;";
$js.= "   var ifpagina;";
$js.= "   if(ns6) {";
$js.= "      ifpagina = document.getElementById(\"id_if_pagina\"); ";
$js.= "      width=window.innerWidth;\n";
$js.= "   } else {";
$js.= "      ifpagina = document.all.if_pagina;";
$js.= "      width = document.body.offsetWidth;";
$js.= "   };";
$js.= "   ifpagina.style.width = width - ".($pag->leftmargin+100).";";
$js.= "};";
$js.= "window.captureEvents(Event.RESIZE);window.onResize = resizeFrame;";
  

$pag->addScript($js);


$js = "function mandaPagina() {";
$js.= "   if(ns6) {";
$js.= "      ifpagina = document.getElementById(\"id_if_pagina\"); ";
$js.= "   } else {";
$js.= "      ifpagina = document.all.if_pagina;";
$js.= "   };";
$js.= "   window.location = '$urlferramentas/email.php?acao=A_compo&frm_mensagem=\"'+ifpagina.src+'\"';";
$js.= "};";

$pag->addScript($js);
$pag->add("<p align=center><a class=\"paginas\" href=\"$urlferramentas/email/email.php?acao=A_compor&frm_mensagem=".urlencode($iframe_url)."\" >$lang[enviar_pagina]</a>");


$pag->imprime();


?>
