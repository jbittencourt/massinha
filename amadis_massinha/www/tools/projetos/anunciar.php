<?
$sem_login=1;
include("../../config.inc.php");
include_once("$pathtemplates/amtprojeto.inc.php");
include_once("$pathtemplates/ambox.inc.php");
include_once("$pathtemplates/ampagebox.inc.php");
include_once("$pathuserlib/amanuncio.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AMTProjeto();

$tableHeader = "<table width=97%><tr><td class=\"tdtema\">";
$tableFooter = "</td></tr></table>";

$itens["&laquo;&nbsp;".$lang[voltar]] = "$url/index.php";
$itens["&laquo;&nbsp;".$lang[ir_projeto]] = "$urlferramentas/projetos/editarprojeto.php?frm_codProjeto=$_REQUEST[frm_codProjeto]";

$pag->setSubMenu($itens,"comum");

$pag->add("<br><br>");

if($_REQUEST[acao]=="A_cria_anuncio"){
  $anuncio = new AMAnuncio();
  $anuncio->desTituloAnuncio = $_REQUEST[frm_desTituloAnuncio];
  $anuncio->desAnuncio = $_REQUEST[frm_desAnuncio];
  $anuncio->codProjeto = $_REQUEST[frm_codProjeto];
  $anuncio->tempo = time();
  $anuncio->salva();

  $tab = new AMBox();
  $tab->add("$lang[anun_succ]");
  $tab->addItem("$lang[voltar]","$urlferramentas/projetos/editarprojeto.php?frm_codProjeto=$anuncio->codProjeto");
  $pag->add($tab);
  $pag->imprime();
  die();
}

$empty_list = array("codAnuncio","codProjeto","tempo");//desTituloAnuncio,desAnuncio
$form = new WsmartForm("AMAnuncio","anuncioBox","$urlferramentas/projetos/anunciar.php?acao=A_cria_anuncio",$empty_list);
$form->setCancelOff();

$form->componentes[codProjeto] = new WHidden("frm_codProjeto",$_REQUEST[frm_codProjeto]);
$form->componentes[desTituloAnuncio]->fdesign = WFORMEL_DESIGN_STRING_DEFINED;
$form->forceToText("desTituloAnuncio",30,"");
$form->componentes[desAnuncio]->fdesign = WFORMEL_DESIGN_STRING_DEFINED;


$form->setDesign(WFORMEL_DESIGN_STRING_DEFINED);
$a = "<td valign= align=rigth class=\"fontgray\">";
$br = "<br>";
$b = "</td></tr><tr>";

$str = "$a <b>{LABEL_FRM_DESTITULOANUNCIO}</b> $br {FORM_EL_FRM_DESTITULOANUNCIO} $b";
$str.= "$a <b>{LABEL_FRM_DESANUNCIO}</b>$br {FORM_EL_FRM_DESANUNCIO} $b";
$str.="<TD COLSPAN=2 ALIGN=RIGHT>{FORM_EL_SUBMIT_BUTTONS}</TD></TR>";

$form->setDesignString($str,1);


$pag->add($tableHeader);
$pag->add($form);
$pag->add($tableFooter);


$pag->imprime();


?>