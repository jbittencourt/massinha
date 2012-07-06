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

$lst = $_SESSION[ambiente]->listaProjetos();
if (!empty($lst->records)){
  foreach ($lst->records as $proj) {
    $sleng = strlen($proj->desProjeto);
    if(!($sleng > 200)){
      $desProjeto = $proj->desProjeto;
    }else{
      $desProjeto = substr($proj->desProjeto,0,200);
      $desProjeto .= " ...";
    }                
    $text = "<div class=\"fontgray\"><a class=\"projeto\" href=\"projeto.php?frm_codProjeto=$proj->codProjeto\">";
    $text .= "$proj->desTitulo</a><br>".$desProjeto."</div>";
    $tab->addItem($text);
  }
}


$pag->add($tab);

$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();





?> 