<?

include("../../config.inc.php");
include_once("$pathuserlib/amarea.inc.php");
include_once("$pathuserlib/amescola.inc.php");
include_once("$pathtemplates/aeprojeto.inc.php");
include_once("$pathtemplates/aebox.inc.php");

$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AEProjeto();


if (empty($_REQUEST[frm_codEscola])){

  $itens["&laquo;&nbsp;".$lang[voltar]] = "$urlferramentas/projetos/projetos.php";
  $pag->setSubMenu($itens,"projeto");
  $escolas = $_SESSION[ambiente]->listaEscolas();

}else{

  $itens["&laquo;&nbsp;".$lang[voltar]] = "$urlferramentas/projetos/porescola.php";
  $pag->setSubMenu($itens,"projeto");
  $projetos = $_SESSION[ambiente]->listaProjetosEscola($_REQUEST[frm_codEscola]);

}


$tab = new AEBox();
if (empty($_REQUEST[frm_codEscola])) {
  $pag->add ("<br><font class=\"comum\">$lang[escola_escola]<br><br></font>");
  if (!empty($escolas->records)) {
    foreach ($escolas->records as $escola) {
      $tab->addItem ($escola->nomEscola, "porescola.php?frm_codEscola=".$escola->codEscola);
    }
  }
  $pag->add ($tab);

} else {

  $escola = new AMEscola($_REQUEST[frm_codEscola]);
  $pag->add ("<br><font class=\"comum\">$lang[projetos_da_escola]:&nbsp;".$escola->nomEscola."<br><br></font>");

  if (!empty($projetos->records)) {
    foreach ($projetos->records as $proj) {
      $tab->addItem($proj->desTitulo, "projeto.php?frm_codProjeto=".$proj->codProjeto);
    }
  }
  $pag->add ($tab);
}

$pag->imprime();


?>