<?php
include("../../config.inc.php");


$ui = new RDui("userinfo", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

if (empty($_REQUEST[frm_codUser])) {
    echo "erro fatal";
    die();
}
$user = new AMUser($_REQUEST[frm_codUser]);
$escolas = $_SESSION[ambiente]->listaEscolas();
$diario = $user->listaDiario();

$pag = new AEPaginas();
$pag->setSubMenu(array($lang[voltar]=>"paginas.php"),"comum");


$pag->add ("<br><p class=\"fontorange\"><b><font size=+1>".$user->nomPessoa."</font></b></p>");
$pag->add ("<p class=\"comum\" <br>");

if ($user->flaSuper == "1") $admin = "($lang[admin])";
$pag->add ("$lang[nome_user]: ".$user->nomUser.$admin."<br>");

$pag->add ("$lang[nascimento]: ".date("d/n/Y", $user->datNascimento)."<br>");
$pag->add ("$lang[datcadastro]: ".date("d/n/Y", $user->tempo)."<br>");

$pag->add ("$lang[email]: ".$user->strEMail."<br>");

if (!empty($escolas->records)) {
    foreach ($escolas->records as $escola) {
        if ($escola->codEscola == $user->codEscola) {
            $nomEscola = $escola->nomEscola;
        }
    }
}

if (!empty($nomEscola)) {
    $pag->add ("$lang[escola]: ".$nomEscola."<br>");
}

$pag->add ("<br>");


$pag->add("<table border=0 width=100%>");
$pag->add("<tr>");
$pag->add("<td width=50% valign=top>");


//PROJETOS

$box = new AEBox();
$box->setTitle("<div class=\"fontorange\"><font size=+1>$lang[projetos_participa]</font></div>","","");
$box->SetClass("fontgray");

$projetos = $user->listaProjetos();
if(!empty($projetos->records)) {
    foreach($projetos->records as $proj) {
        $nome = $proj->desTitulo;
        if(empty($nome)) $nome = $lang[projeto_sem_titulo];
        $box->addItem($nome,"$urlferramentas/projetos/projeto.php?frm_codProjeto=".$proj->codProjeto);
    }
}


$pag->add($box);
$pag->add("<br><br>");

//FORUNS

$box = new AEBox();
$box->setTitle("<div class=\"fontorange\"><font size=+1>$lang[foruns_participou]</font></div>","","");
$box->SetClass("fontgray");

$foruns = $user->listaForunsParticipou();
if(!empty($foruns->records)) {
    foreach($foruns->records as $forum) {
        $box->addItem($forum->nomForum,"$urlferramentas/forum/mensagens.php?forum=".$forum->codForum);
    }
}


$pag->add($box);
$pag->add("</td><td>");


//DIARIO

if (!empty($diario->records)) {

    $pag->add ("<div class=\"fontorange\"><font size=+1>Di�rio:</font></div> <br><br>");
    $tab = new AEPageBox("5");
  //$tab->setTitle("img_tit_forum_mensagens.gif");
    $cont =0;
    foreach ($diario->records as $texto) {

        $coments = $texto->listaComentarios();
        $tab->addItem ("<div class=\"comum\" align=justify><b>".date("d/n/Y", $texto->tempo)."&nbsp;-&nbsp;</b>".$texto->desTexto."<div align=right><a class=\"comum\" href=\"$urlferramentas/diario/comentarios.php?origem=info&frm_codTexto=".$texto->codTexto."&user=".$user->codUser."\">$lang[ver_coment]&nbsp;(".$coments->numRecs.")</a></div></div><br>");

    }
    $pag->add ($tab);
}


$pag->add("</td>");
$pag->add("</table>");

$pag->imprime();

?>