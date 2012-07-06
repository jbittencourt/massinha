<?

include("../../config.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AEProjeto();
$lista = $_SESSION[usuario]->listaProjetos();

$pag->add("<br><table border=0 cellpadding=0 cellspacing=0>");

//coluna da esquerda
$pag->add("<tr><td width=50% class=\"fontgray\"");
$pag->add(" valign=\"top\">");

//meus projetos
$mProj = new AEBox();

$mProj->SetTitle("img_tit_meus_projetos.gif");
$mProj->add("<div class=\"fontgray\">");

if(!empty($lista->records)){
  foreach($lista->records as $projM){
    $proj = new AMProjeto($projM->codProjeto);
    $mProj->addItem($proj->desTitulo,"$url/ferramentas/projetos/editarprojeto.php?frm_codProjeto=".$proj->codProjeto);
  }
}else{
  $mProj->add("&nbsp;&nbsp;".$lang[erro_proj]);
}


$mProj->add("</div>");

$pag->add($mProj);//adiciona na pagina a caixa meus projetos

$pag->add("<p></p>");

//criar projetos
$cProj = new AEBox();

$cProj->SetTitle("img_tit_criar_projeto.gif");
$cProj->addItem("$lang[criar_proj]","$urlferramentas/projetos/cadastraprojeto.php");

$pag->add($cProj);//adiciona na pagina a caixa criar projetos


//coluna do meio
$pag->add("</td><td><img src=\"$urlimagens/dot.gif\" width=\"30\" height=\"30\">");


//coluna da direita
$pag->add("</td><td width=\"30\" valign=\"top\">");

//projetos por area
$pArea = new AEBox();

$pArea->SetTitle("img_tit_projetos_area.gif");
$pArea->add("<div class=\"fontgray\">");


$lst = $_SESSION[ambiente]->listaAreas();

if(!empty($lst->records)) {
$treeLst = new AETreeAreas($lst);

$pArea->add($treeLst);
$pArea->add("<br>");
$pArea->SetClass("projeto");
$pArea->SetItemAlign("right");
$pArea->addItem($lang[ver_proj],"$urlferramentas/projetos/listaprojetos.php");
$pArea->addItem($lang[projetos_escola],"porescola.php");

$pArea->add("</div>");
    
} else {
    $pArea->add($lang['erro_no_area']);
}

$pag->add($pArea);//adiciona na pagina a caixa projetos por area
$pag->add("<p></p>");

//projetos mais visitados
$pMvis = new AEBox();

$lst = $_SESSION[ambiente]->listaTopProjetos();
if(!empty($lst)){
  foreach($lst as $proj){
   $pMvis->addItem($proj->desTitulo,"$urlferramentas/projetos/projeto.php?frm_codProjeto=$proj->codProjeto");
  }
}
  

$pMvis->SetTitle("img_tit_projetos_visitados.gif");


$pag->add($pMvis);//adiciona na pagina a caixa projetos mais visitados

$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();


?>