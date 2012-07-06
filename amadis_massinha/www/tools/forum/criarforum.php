<?php

include_once("../../config.inc.php");


$ui = new RDui("forum", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$plataformas = $_SESSION[ambiente]->listaPlataformas();

if (isset($_REQUEST[acao])) {
    switch($_REQUEST[acao]) {
        case "A_forum_make":
            $temp = new AMForumAmadis($_REQUEST[frm_codForum]);
            $temp->nomForum = $_REQUEST[frm_nomForum];
            $temp->tipoPai = $_REQUEST[frm_tipoPai];
            $temp->codPai = $_REQUEST[frm_codPai];

            switch($_REQUEST[frm_tipoPai]) {
                case "P":
                    $param = "projeto=".$_REQUEST[frm_codPai];
                    break;

                case "L":
                    $param = " ";
                    break;
            }

            $temp->flaAllowPost = $_REQUEST[frm_flaAllowPost];
            $temp->flaAllowView = $_REQUEST[frm_flaAllowView];
            $temp->tempo = time();
            $temp->salva();

            Header("Location: forum.php?$param");
            die();
    }
}

$pag = new AEForum();

if ($_REQUEST[projeto] != "") {
    $proj = new AMProjeto($_REQUEST[projeto]);
    $classe = "P";
    $pai = $_REQUEST[projeto];
    $pag->add ("<br><div align=right><a href=\"forum.php?projeto=".$proj->codProjeto."\" class=\"comum\">$lang[voltar]</a></div><br>");
}

if($_REQUEST[destino] == "escola") {
    $classe = "L";
    if (!empty($plataformas->records)) {
        foreach ($plataformas->records as $plataforma) {
            if ($plataforma->strIDPlataforma == "amadis_escola") {
                $pai = $plataforma->codPlataforma;
            }
        }
    }
}

$pag->add ("<br>");

$pag->add ("<table border=0 width=\"100%\" cellpadding=0 cellspacing=0>");
$pag->add ("<td><td width=\"10%\" valign=\"top\">&nbsp;<p>&nbsp;</td>");
$pag->add ("<td width=\"80%\" valign=\"top\">");


if(!empty($_REQUEST[frm_codForum])) {
    $forum = new AMForumAmadis($_REQUEST[frm_codForum]);
    $hidden = array("codForum","tipoPai","codPai");
    $nao_mostrar = array("tempo");
}
else {
    $hidden = array("tipoPai","codPai");
    $nao_mostrar = array("codForum","tempo");
}
if ($classe == "L") {
    $hidden[] = "flaAllowPost";
    $hidden[] = "flaAllowView";
}

$form = new WSmartForm("AMForumAmadis","forum_cria",$_SERVER[PHP_SELF]."?acao=A_forum_make",$nao_mostrar,$hidden);
$form->componentes[tipoPai]->setValue($classe);
$form->componentes[codPai]->setValue($pai);
if ($classe == "L") {
    $form->componentes[flaAllowPost]->setValue($classe);
    $form->componentes[flaAllowView]->setValue($pai);
}
$form->setLabelClass("comum");
$form->setDesign("WFORMEL_DESIGN_SIDE");

$pag->add ($form);
$pag->add ("</td><td width=\"10%\" valign=\"top\">&nbsp;<p>&nbsp;</td></table>");

$pag->add ("<br>");

$pag->imprime();
?>
