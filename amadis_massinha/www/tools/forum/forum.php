<?php
include_once("../../config.inc.php");

$ui = new RDui("forum", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$foruns = $_SESSION[ambiente]->listaForunsAmadis();
$plataformas = $_SESSION[ambiente]->listaPlataformas();
$projetos = $_SESSION[usuario]->listaProjetos();

$pag = new AEForum();
$pag->add ("<br>");

if ($_REQUEST[projeto]) {
    $proj = new AMProjeto($_REQUEST[projeto]);
    $categ = "projeto";
    $classe = "P";
    $voltar = "&projeto=".$_REQUEST[projeto];
}


if (!$classe) {
    $itens[$lang[criar_forum_geral]] = "criarforum.php?destino=escola";
}
else {
    switch ($classe) {
        case "P":
            $itens[$lang[criar_forum_projeto]] = "criarforum.php?projeto=".$_REQUEST[projeto];
    }
}

$pag->setSubMenu($itens,"comum");

//box com a lista de foruns gerais do ambiente
if (empty($classe)) {
    $tabgeral = new AEBox();
    $tabgeral->setTitle("img_tit_foruns_gerais.gif");
    $tabgeral->setClass("comum");

    if(!empty($foruns->records)) {
        foreach ($foruns->records as $forum)  {
            if ($forum->tipoPai == "L") {
                $link = "mensagens.php?forum=".$forum->codForum;
                $tabgeral->addItem($forum->nomForum, $link);
            }
        }
    }
    $pag->add($tabgeral);

    $tabproj = new AEBox();
    $tabproj->setTitle("img_tit_foruns_dos_meus_pro.gif");
    $tabproj->setClass("comum");

    if(!empty($foruns->records)) {
        $listaproj = array();
        foreach ($foruns->records as $forum)  {
            if ($forum->tipoPai == "P") {
                if (!empty ($projetos->records)) {
                    foreach ($projetos->records as $projeto) {
                        if ($forum->codPai == $projeto->codProjeto) {
                            if (!$listaproj[$projeto->codProjeto]) {
                                $proj = new AMProjeto($projeto->codProjeto);
                                $listaproj[$projeto->codProjeto] = array();
                                $listaproj[$projeto->codProjeto][] = $proj->desTitulo;
                            }
                            $link = "mensagens.php?forum=".$forum->codForum.$voltar;
                            $listaproj[$projeto->codProjeto][] = array($forum->nomForum, $link);
                        }
                    }
                }
            }
        }
        if (!empty($listaproj)) {
            foreach ($listaproj as $listpr) {
                $tabproj->addItem ($listpr);
            }
        }
    }
    $pag->add ("<br>");
    $pag->add ($tabproj);
}

//f�runs dos projetos
if ($classe == "P") {
    $pag->add ("<br><font class=\"comum\">$lang[foruns_projeto]:&nbsp;".$proj->desTitulo."</font><br>");
    $tabproj = new AEBox();
  //$tabproj->setTitle("img_tit_foruns_dos_meus_pro.gif");
    $tabproj->setClass("comum");

    if(!empty($foruns->records)) {
        $listaproj = array();
        foreach ($foruns->records as $forum)  {
      //$_SESSION[forum_perm][$forum->codForum][can_view] = ($allow || $forum->flaAllowView || $forum->flaAllowPost);
      //$_SESSION[forum_perm][$forum->codForum][can_post] = ($allow || $forum->flaAllowPost);
            if ($forum->tipoPai == "P" and $forum->codPai == $_REQUEST[projeto]) {
                $tabproj->addItem ($forum->nomForum, "mensagens.php?forum=".$forum->codForum.$voltar);
            }
        }
    }
    $pag->add ("<br>");
    $pag->add ($tabproj);
}


$pag->add ("<br>");
$pag->imprime();

?>
