<?php
include("../../config.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);



if(empty($_REQUEST[frm_codProjeto])) {
  die("Voce precisa definir um c�digo para o projeto");
}

if(!empty($_REQUEST[acao])) {
  switch($_REQUEST[acao]) {
  case "A_noticia_make":
    $novidade = new AMNovidade();
    $novidade->desNovidade = $_REQUEST[frm_desNovidade];
    $novidade->codProjeto = $_REQUEST[frm_codProjeto];
    $novidade->salva();
    break;
  }
}


$pag = new AEProjeto();

$item["&laquo;&nbsp;$lang[voltar]"] = "$urlferramentas/projetos/projetos.php";
$pag->setSubMenu($item,"projeto");

//coluna da esquerda
$pag->add("<table border=0 cellpadding=0 cellspacing=0 width=\"100%\"><tr><td class=\"fontgray\"");
$pag->add(" valign=top><br>");

$proj = new AMProjeto($_REQUEST[frm_codProjeto]);

//lista os projetos do usuario
//coluna da esquerda
//descri��o
$describ = new AEBox();
$describ->SetTitle("img_tit_projeto.gif");
$describ->add("<div valign=\"top\" class=\"fontgray\"><b>$lang[ferramentas]</b></div>");

$describ->SetClass("projeto");
$describ->addItem("$lang[a_pagina]","$urlferramentas/paginas/vepagina.php?frm_codProjeto=$proj->codProjeto");
$describ->addItem("$lang[os_foruns]","$urlferramentas/forum/forum.php?projeto=$proj->codProjeto");
$describ->addItem("$lang[editar_proj]","$urlferramentas/projetos/alteraprojeto.php?frm_codProjeto=$proj->codProjeto");
$describ->addItem("$lang[equipes_proj]","$urlferramentas/projetos/equipeprojeto.php?frm_codProjeto=$proj->codProjeto");
$describ->addItem("$lang[upload_proj]","$urlferramentas/upload/upload.php?codProjeto=$proj->codProjeto");
$describ->add("</div>");

$pag->add($describ);//adiciona na pagina a caixa descri�ao

$pag->add("<p><br>");

//LISTA EQUIPE
$bequipe = new AEBox();

$equipe = $proj->listaEquipeCompleta();

$bequipe->SetClass("fontgray"); 
$bequipe->add("<p><div class=\"fontgray\">$lang[equipe]<br>");


if (!empty($equipe->records)) {
  foreach ($equipe->records as $membro) {
    $orient="";
    if($membro->codUser == $proj->codOrientador) $orient = "<font size=\"-2\">($lang[orientador])</font>";
    $bequipe->add("&raquo; ".$membro->nomPessoa."$orient<br>");
  }
  $bequipe->add("</div></p>");
}else{
  $bequipe->add("$lang[erro_equipe]</div></p>");
}

$bequipe->add("</div></p>");
$pag->add($bequipe);//adiciona a pagina a caixa de areas



//coluna do meio
$pag->add("</td><td><img src=\"$urlimagens/dot.gif\" width=\"30\" height=\"30\">");


//coluna da direita
$pag->add("</td><td width= valign=\"top\"><br>");

//comentarios
$comentario = new AEBox();

$comentario->SetTitle("img_tit_proj_comentarios.gif");

$comentProj = $proj->listaComentariosProjeto();

if(!empty($comentProj->records)){
  $i=0;
  $comentario->add("<div class=\"fontgray\">");
  foreach($comentProj->records as $coment){
    if($i < 3){
      $comentario->add("<b>".$coment->desNome.":</b>&nbsp;".$coment->desComentario."<br><br>");
    }
    $i++;
  }
  $comentario->add("</div>");
}else{
  $comentario->add("<div class=\"fontgray\">&nbsp;&nbsp;$lang[erro_nov]</div>");
}

$comentario->SetItemAlign("right");
$comentario->Setclass("projeto");
$comentario->addItem($lang[ver_com],"$urlferramentas/projetos/comentariosprojeto.php?frm_codProjeto=".$_REQUEST[frm_codProjeto]);

$pag->add($comentario);//adiciona a caixa de comentarios na pagina

$pag->add("<p></p>");


//novidades
$nov = new AEBox();
$nov->setclass("projeto");
$nov->SetTitle("img_tit_novidades_projetos.gif");

$novProj = $proj->listaNovidades();
if(!empty($novProj->records)){
  $i=0;
  foreach($novProj->records as $np){
    if($i < 3){
      $nov->add("<div class=\"fontgray\">$np->desNovidade</div>");
    }
    $i++;
  }
    
}else{
  $nov->add("<div class=\"fontgray\">&nbsp;&nbsp;$lang[erro_nov]</div>");
}

$nov->addItem($lang[ver_nov],"$urlferramentas/projetos/novidadesprojeto.php?frm_codProjeto=".$_REQUEST[frm_codProjeto]);
$nov->SetItemAlign("right");
$pag->add($nov);//adiciona na pagina a caixa de novidades
$pag->add("<p></p>");


$envNovidade = new AEBox();
$envNovidade->SetTitle("img_tit_env_novidade.jpg");

$empty_list = array("codNovidade","codProjeto","tempo");

$form = new WSmartForm("AMNovidade","form_novidade",$_SERVER[PHP_SELF],$empty_list);

$form->setCancelOff();

$form->setDesign(WFORMEL_DESIGN_SIDE);
$form->spacing = 0;
  
$form->addComponent("desNovidade",new WTextArea("frm_desNovidade",6,25));
$form->addComponent("codProjeto",new WHidden("frm_codProjeto",$_REQUEST[frm_codProjeto]));

$form->addComponent("acao",new WHidden("acao","A_noticia_make"));
$envNovidade->add($form);


$pag->add($envNovidade);




$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();

?> 