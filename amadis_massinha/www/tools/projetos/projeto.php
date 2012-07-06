<?php
include("../../config.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);



if(empty($_REQUEST[frm_codProjeto])) {
    die("Voce precisa definir um c�digo para o projeto");
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

//coluna da esquerda
$pag->add("<table border=0 cellpadding=0 cellspacing=0 width=\"100%\"><tr><td class=\"fontgray\"");
$pag->add(" valign=\"top\"><br>");

$proj = new AMProjeto($_REQUEST[frm_codProjeto]);

//lista os projetos do usuario
//coluna da esquerda
//descri��o
$describ = new AEBox();

$describ->setTitle("img_tit_projeto.gif");

$describ->add("<div class=\"fontgray\"><b>$proj->desTitulo</b></div>");
$describ->add("<p><div class=\"fontgray\" align=justify>$lang[frm_desProjeto]:<br>$proj->desProjeto</div>");

$describ->add("<p></p>");

$describ->add("<div class=\"fontgray\">$lang[conheca]:");

$describ->SetClass("projeto");

$describ->addItem("$lang[a_pagina]","$urlferramentas/paginas/vepagina.php?frm_codProjeto=$proj->codProjeto");
$describ->addItem("$lang[os_foruns]","$urlferramentas/forum/forum.php?projeto=$proj->codProjeto");
$describ->add("</div>");

$pag->add($describ);//adiciona na pagina a caixa descri�ao

$pag->add("<p></p>");

//status
$status = new AEBox();

$statusProj = $proj->listaStatus();
$status->add("<p><div class=\"fontgray\">$lang[status]:<br>&raquo; ".$statusProj->records[0]->desStatus."</div></p>");

$pag->add($status);//adiciona a pagina a caixa de status

$pag->add("<p></p>");

//areas do projeto
$areas = new AEBox();

$areasProj = $proj->listaAreas();
$areas->SetClass("fontgray");
$areas->add("<p><div class=\"fontgray\">$lang[area]<br>");

if (!empty($areasProj->records)) {
    foreach ($areasProj->records as $areaP) {
        $area = new AMArea($areaP->codArea);
        $areas->add("&raquo; ".$area->nomArea."<br>");
    }
    $areas->add("</div></p>");
}else{
    $areas->add("$lang[erro_area]</div></p>");
}

$areas->add("</div></p>");
$pag->add($areas);//adiciona a pagina a caixa de areas



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
$pag->add("</td><td width=30 valign=\"top\"><br>");

//novidades
$nov = new AEBox();
$nov->setclass("projeto");
$nov->SetTitle("img_tit_novidades_projetos.gif");

$novProj = $proj->listaNovidades();
if(!empty($novProj->records)){
    $i=0;
    foreach($novProj->records as $np){
        if($i < 3){
            $nov->add("<div class=\"fontgray\" align=justify>$np->desNovidade</div>");
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

//comentarios
$comentario = new AEBox();

$comentario->SetTitle("img_tit_proj_comentarios.gif");

$comentProj = $proj->listaComentariosProjeto();

if(!empty($comentProj->records)){
    $i=0;
    $comentario->add("<div class=\"fontgray\" align=justify>");
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

$envComentario = new AEBox();

$envComentario->SetTitle("img_tit_proj_enviar_comenta.gif");

$empty_list = array("codComentario","tempo","codProjeto","codUser");

if(!empty($_SESSION[usuario])) {
    $empty_list[] = "desNome";
};

$form = new WSmartForm("AMComentarioProjeto","form_comenta",$_SERVER[PHP_SELF],$empty_list);

$form->setCancelOff();

$form->setDesign(WFORMEL_DESIGN_OVER);
$form->spacing = 0;

$form->addComponent("desComentario",new WTextArea("frm_desComentario",6,25));
$form->addComponent("codProjeto",new WHidden("frm_codProjeto",$_REQUEST[frm_codProjeto]));
$form->addComponent("desNome",new WHidden("frm_desNome",$_SESSION[usuario]->nomPessoa));

$form->addComponent("acao",new WHidden("acao","A_comentario_make"));
$envComentario->add($form);


$pag->add($envComentario);

$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();

?>