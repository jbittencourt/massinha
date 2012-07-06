<?php
include("../../config.inc.php");

$ui = new RDui("diario");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$submenu[$lang['listar_diarios']] = $urlferramentas . "/diario/listaDiarios.php";

$pag = new AEDiario();
$pag->setSubMenu($submenu,"comum");

if(!empty($_REQUEST[acao])) {
    switch($_REQUEST[acao]) {
        case "A_add_texto":
            if (!empty($_REQUEST[frm_desTexto])) {
                $tmp = new AMDiario();
                $tmp->tipoPai = $_REQUEST[frm_tipoPai];
                $tmp->codPai = $_REQUEST[frm_codPai];
                $tmp->desTexto = $_REQUEST[frm_desTexto];
                $tmp->tempo = time();
                $tmp->salva();
            }
            break;
        case "A_del":
            if(!empty($_REQUEST[frm_codTexto])) {
                $texto = new AMDiario($_REQUEST[frm_codTexto]);

                if($texto->novo) {
                    $mens[] = $lang[entrada_nao_exite];
                    break;
                }
                switch($texto->tipoPai) {
                    case "U":
                        $_REQUEST[frm_codUser] = $texto->codPai;
                        break;
                    case "P":
                        $_REQUEST[frm_codProjeto] = $texto->codPai;
                        break;
                }

      //verifica se o usu�rio pode apagar essa mensagem
                $canDelete = (($_SESSION[ususario]->codUser==$texto->codUser) && ($texto->tempo>=$timeout)) OR $_SESSION[usuario]->eSuper();
                if(!$canDelete) {
                    $mens[] = $lang[nao_pode_deletar];
                }
                else {
                    $coments = $texto->listaComentarios();

                    if($coments->numRecs>0) {
                        $coments->delete();
                    }
                    
                    $texto->deleta();
                    $mens[] = $lang[entrada_deletada];

                }
            }
    }
}

//seta as variaveis de acordo com o tipo do diario
if (empty($_REQUEST[frm_codUser]) and empty($_REQUEST[frm_codProjeto])) {
    $tipoPai = "U";
    $codPai = $_SESSION[usuario]->codUser;
    $user = &$_SESSION[usuario];
    $textos = $user->listaDiario();
    $canpost = 1;
}
else {
    if (!empty($_REQUEST[frm_codUser])) {
        $tipoPai = "U";
        $codPai = $_REQUEST[frm_codUser];
        $user = new AMUser($codPai);
        $textos = $user->listaDiario();
        $hiddenname = "frm_codUser";
        $hidden = new WHidden("frm_codUser",$codPai);
        if ($user->codUser == $_SESSION[usuario]->codUser) $canpost = 1;
        else $canpost = 0;
    }
    if (!empty($_REQUEST[frm_codProjeto])){
        $tipoPai = "P";
        $codPai = $_REQUEST[frm_codProjeto];
        $proj = new AMProjeto($codPai);
        $textos = $proj->listaDiario();
        $hiddenname = "frm_codProjeto";
        $hidden = new WHidden("frm_codProjeto",$codPai);
        if ($proj->eMembro($_SESSION[usuario]->codUser) == "1" or $proj->eOrientador($_SESSION[usuario]->codUser) == "1") {
            $canpost = 1;
            $pag->setSubMenu(array($lang[voltar] => "$url/ferramentas/projetos/editarprojeto.php?frm_codProjeto=".$proj->codProjeto));
        }
        else {
            $canpost = 0;
            $pag->setSubMenu(array($lang[voltar] => "$url/ferramentas/projetos/projeto.php?frm_codProjeto=".$proj->codProjeto));
        }
    }
}

//adiciona o titulo
$pag->add("<br>");

if ($tipoPai == "P") {
    $pag->add ("<font class=fonttit1>$lang[diario_proj]:&nbsp;".$proj->desTitulo."</font>");
}
if ($tipoPai == "U") {
    if ($user->codUser == $_SESSION[usuario]->codUser) {
        $pag->add ("<font class=fonttit1>$lang[meu_diario]</font>");
    }
    else {
        $pag->add ("<font class=fonttit1>$lang[diario_user]:&nbsp;".$user->nomPessoa."</font>");
    }
}
$pag->add("<br><br>");


if(!empty($mens)) {
    foreach($mens as $men) {
        $pag->add("<div class=\"error\">$men</div>");
    }
}

//se a pessoa puder postar, aparece a janela para postar
if($canpost == "1") {
    $pag->add("<table width=\"100%\">");

    $pag->add("<tr><td>");
    $pag->add("<div class=\"fonttit1\">$lang[anotacoes_hoje]</div>");
    $pag->add("<div align=\"right\" class=\"fontgray\">$lang[hoje] ".date($lang[formato_data],time())."</div>");
    $pag->add("</td></tr>");

    $form = new WSmartform("AMDiario","diario","diario.php?acao=A_add_texto",array("tempo","codTexto"),array("tipoPai","codPai"));
    $temp = &$form->componentes[desTexto];

    $temp->setCols(60);
    $temp->setRows(10);

    $form->setCancelOff();
    $form->componentes[tipoPai]->setValue($tipoPai);
    $form->componentes[codPai]->setValue($codPai);

    if (!empty($hidden)) $form->addComponent($hiddenname,$hidden);

    $pag->add ("<tr><td align=center>");
    $pag->add($form);
    $pag->add ("</td></tr>");

    $pag->add ("</td></tr></table>");

  //linha
    $pag->addLine();
}

$pag->add ("<br>");
$tab = new AEPageBox(5);
$tab->setTitle($lang[anotacoes_anteriores]);

$js = "function delDiario(codtexto) { ";
$js.= "  var r= confirm('$lang[tem_certeza_deletar]'); ";
$js.= "  if(r) { window.location = \"diario.php?acao=A_del&frm_codTexto=\"+codtexto; return 1;}; ";
$js.= "  return 0;";
$js.= "};";

$pag->addScript($js);

//diminui o tempo atual - 1h = 3600 segundos
$timeout = time() - 3600;
if (!empty($textos->records)) {
    foreach ($textos->records as $texto) {
        $coments = $texto->listaComentarios();

        $del = "";
        if((($coments->numRecs==0) && ($texto->tempo>=$timeout)) OR $_SESSION[usuario]->eSuper()) {
            $del = "<a href=\"#\" onClick=\"delDiario(".$texto->codTexto.")\"><img src=\"$urlimagens/trash_mini.png\" border=0></a>";
        }

        $coment = "<div align=right><a class=\"comum\" href=\"comentarios.php?frm_codTexto=".$texto->codTexto."\">$lang[comentarios] (".$coments->numRecs.")</a></div>";
        $tab->addItem ("<p align=justify><font class=\"fontgray\"><b>".date("d/n/Y", $texto->tempo)."&nbsp;-&nbsp;</b></font>".$texto->desTexto.$del.$coment."<br></p>");
    }
}
else $tab->add ("<font class=\"fontgray\"><i>$lang[nenhum_item]</i></font>");

$pag->add ($tab);
$pag->add ("<br>");

$pag->imprime();







?>