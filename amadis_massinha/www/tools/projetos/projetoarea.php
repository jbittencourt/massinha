<?

include("../../config.inc.php");
include_once("$pathuserlib/amarea.inc.php");
include_once("$pathtemplates/aeprojeto.inc.php");
include_once("$pathtemplates/aepagebox.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AEProjeto();

$itens["&laquo;&nbsp;".$lang[voltar]] = "$urlferramentas/projetos/projetos.php";
$pag->setSubMenu($itens,"projeto");

$pag->add("<br><table border=0 cellpadding=0 cellspacing=0 width=\"100%\">");

$pag->add("<tr><td width=50% class=\"fontgray\"");
$pag->add(" valign=\"top\">");

$tab = new AEPageBox(10);
$tab->setLinkClass("projeto");
$tab->SetTitle("img_tit_lista_projetos.gif");

$area = new AMArea($_REQUEST[frm_codArea]);
$lst = $area->listaProjetos();
if (!empty($lst->records)){
  foreach ($lst->records as $proj) {
    $projArea = new AMProjeto($proj->codProjeto);
    $sleng = strlen($projArea->desProjeto);
    if(!($sleng > 200)){
      $desProjeto = $projArea->desProjeto;
    }else{
      $desProjeto = substr($projArea->desProjeto,0,200);
      $desProjeto .= " ...";
    }                
    $text = "<div class=\"fontgray\"><a class=\"projeto\" href=\"projeto.php?frm_codProjeto=$projArea->codProjeto\">";
    $text .= "$projArea->desTitulo</a><br>".$desProjeto."</div>";
    $tab->addItem($text);
  }
}else{
  $tab->add("<div class=\"comum\"><i>&nbsp;$lang[proj_narea]</i></div>");
}


$pag->add($tab);

$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();





?>