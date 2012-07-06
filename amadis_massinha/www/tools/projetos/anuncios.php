<?
$sem_login=1;
include("../../config.inc.php");
include_once("$pathtemplates/amtprojeto.inc.php");
include_once("$pathtemplates/ambox.inc.php");
include_once("$pathtemplates/ampagebox.inc.php");
include_once("$pathuserlib/amanuncio.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AMTProjeto();

$itens["&laquo;&nbsp;".$lang[voltar]] = "$url/index.php";
$pag->setSubMenu($itens,"comum");

$pag->add("<br><table border=0 cellpadding=0 cellspacing=0>");

//coluna da esquerda
$pag->add("<tr><td class=\"comum\"");
$pag->add(" valign=\"top\">");

$tab = new AMPageBox(10);

$tab->SetTitle($lang[anuncios]);

$lst = $_SESSION[ambiente]->listaAnuncios();
if (!empty($lst->records)){
  foreach ($lst->records as $anun) {
    $sleng = strlen($anun->desAnuncio);
    if(!($sleng > 200)){
      $desAnuncio = $anun->desAnuncio;
    }else{
      $desAnuncio = substr($anun->desAnuncio,0,200);
      $desAnuncio .= " ...";
    }			    
    $text = "<div class=\"fontgray\"><a class=\"comum\" href=\"anuncio.php?frm_codAnuncio=$anun->codAnuncio&frm_codProjeto=$anun->codProjeto\">";
    $text .= "$anun->desTituloAnuncio</a><br>".$desAnuncio."</div>";
    $tab->addItem($text);
  }
}


$pag->add($tab);

$box = new AMBox();
$box->SetItemAlign("right");
$box->SetHtmlItem("l");
$box->addItem("$lang[voltar]","$url/index.php");
$pag->add($box);


//coluna do meio
$pag->add("</td><!--<td><img src=\"$urlimagens/dot.gif\" width=\"30\" height=\"30\">");


//coluna da direita
$pag->add("</td><td width=\"\" valign=\"top\">");



$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>-->");


$pag->add("</td></tr></table>");

$pag->imprime();



?>