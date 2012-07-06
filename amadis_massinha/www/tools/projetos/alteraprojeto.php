<?

include("../../config.inc.php");
include_once("$pathuserlib/amarea.inc.php");
include_once("$pathuserlib/amprojeto.inc.php");
include_once("$pathtemplates/aebox.inc.php");
include_once("$pathtemplates/aeprojeto.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AEProjeto();
$proj = new AMProjeto($_REQUEST[frm_codProjeto]);

$pag->add("<br><table border=0 cellpadding=0 cellspacing=0 width=\"100%\">");

$pag->add("<tr><td width=50% class=\"fontgray\"");
$pag->add(" valign=\"top\">");


if (isset($_REQUEST[acao])) {

  switch($_REQUEST[acao]) {

  case "A_make":

    $proj->loadDataFromRequest();
/*    $proj->nomProjeto = $_REQUEST[frm_desTitulo];
    $proj->desProjeto = $_REQUEST[frm_desProjeto];
    $proj->codOrientador = $_REQUEST[frm_codOrientador];
    $proj->codEscola = $_REQUEST[frm_codEscola];
    $proj->flaEstado = $_REQUEST[frm_flaEstado];
*/

    $proj->salva();


    $pag->add("<center>".$lang[projeto_cadastrado]."<br><br><br>");
    $pag->add("<img src=\"$urlimagens/dot.gif\" height=\"50\" width=\"10\">");
    $pag->add("<a class=\"regular\" href=\"$urlferramentas/projetos/editarprojeto.php?projeto=$_REQUEST[frm_codProjeto]\">$lang[voltar]</a></center>");

    $pag->add ("</td><td width=\"10%\" valign=top>&nbsp;</td></tr></table>");
    $pag->add ("<img src=\"$urlimagens/dot.gif\" height=\"20\" width=\"10\">");

    $pag->imprime();
    die();
    break;
  }
};

global $config_ini;

$tab = new AEBox();
$tab->setTitle("img_tit_criar_projeto.gif");
$tab->add("");
$pag->add($tab);
  
$form = new WSmartForm("AMProjeto","projeto_form","$PHP_SELF?acao=A_make",array("codOwner","tempo","hits"),array("codProjeto"));
$form->setDesign(WFORMEL_DESIGN_OVER);

$escola = $_SESSION[ambiente]->listaEscolas();

$campos = array("codUser","nomUser","nomPessoa");
$users= $_SESSION[ambiente]->listaUsuariosPlataforma($config_ini[Ambiente][plataforma_cod]);

$status = AMProjeto::listaStatus();
$form->setRadioGroup("flaEstado",$status,"codStatus","desStatus");
$form->componentes[flaEstado]->fdesign = WFORMEL_DESIGN_STRING_DEFINED;

$form->setSelect("codOrientador",$users,"codUser","nomPessoa");
$form->componentes[codOrientador]->addOption(0,$lang[sem_orientador]);

$form->setSelect("codEscola",$escola,"codEscola","nomEscola");
$form->componentes[codEscola]->addOption(0,$lang[nenhuma_escola]);


$form->loadDataFromObject($proj);

$form->setDesign(WFORMEL_DESIGN_STRING_DEFINED);
$a = "<td valign= align=rigth class=\"comum\">";
$b = "</td></tr><tr>";
$br = "<BR>";
$str = "$a <b>{LABEL_FRM_DESTITULO}</b> $br {FORM_EL_FRM_DESTITULO} $b";
$str.= "$a <b>{LABEL_FRM_DESPROJETO}</b> $br {FORM_EL_FRM_DESPROJETO} $b";//{TIP_FRM_DESPROJETO}</td>";
$str.= "$a <b>{LABEL_FRM_FLAESTADO}</b> $br {FORM_EL_FRM_FLAESTADO} $b";//{TIP_FRM_DESPROJETO}</td>";
$str.= "$a <b>{LABEL_FRM_CODORIENTADOR}</b> $br {FORM_EL_FRM_CODORIENTADOR} $b";// &nbsp; </td>";
$str.= "$a <b>{LABEL_FRM_CODESCOLA}</b> $br {FORM_EL_FRM_CODESCOLA} $b";// &nbsp; </td>";
$str.="<TD COLSPAN=2 ALIGN=RIGHT>{FORM_EL_SUBMIT_BUTTONS}</TD></TR>";

$form->setDesignString($str,1);
$form->setCancelUrl("$urlferramentas/projetos/editarprojeto.php?frm_codProjeto=$proj->codProjeto");

$pag->add("<table><tr><td class=\"tdgreen\">");
$pag->add($form);
$pag->add("</td></tr></table>");


$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();

?>