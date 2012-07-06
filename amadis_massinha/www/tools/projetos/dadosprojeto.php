<?

include("../../config.inc.php");
include_once("$pathuserlib/amarea.inc.php");
include_once("$pathuserlib/amprojeto.inc.php");
include_once("$pathtemplates/ambox.inc.php");
include_once("$pathtemplates/amtprojeto.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AMTProjeto();

$pag->add("<br><table border=0 cellpadding=0 cellspacing=0 width=\"100%\">");

$pag->add("<tr><td width=50% class=\"fontgray\"");
$pag->add(" valign=\"top\">");


if (isset($_REQUEST[acao])) {

  switch($_REQUEST[acao]) {

  case "A_make":

    $proj = new AMProjeto($_REQUEST[frm_codProjeto]);
    $proj->nomProjeto = $_REQUEST[frm_desTitulo];
    $proj->desProjeto = $_REQUEST[frm_desProjeto];
    $proj->codOrientador = $_REQUEST[frm_codOrientador];
    $proj->codEscola = $_REQUEST[frm_codEscola];
    $proj->flaEstado = $_REQUEST[frm_flaEstado];
    $proj->salva();


    $pag->add("<center>".$lang[projeto_cadastrado]."<br><br><br>");
    $pag->add("<a class=\"regular\" href=\"editarprojeto.php?projeto=$_REQUEST[frm_codProjeto]\">".$lang[voltar_webfolio]."</a></center>");

    $pag->add ("</td><td width=\"10%\" valign=top>&nbsp;</td></tr></table>");
    $pag->add ("<img src=\"$urlimagens/space.gif\" height=\"20\" width=\"10\">");

    $pag->imprime();
    die();
    break;
  }
};

 
$tab = new waBox(1);
$tab->setColor("verde");
$tab->setTipoTitulo("unico", "light");
$tab->asTable=0;
$tab->setTitulo($lang[altera_projeto]);  
       
  
$form = new WSmartForm("AMProjeto","projeto_form","$PHP_SELF?acao=A_make",array("codOwner","tempo","hits"),array("codProjeto"));
$form->setDesign(WFORMEL_DESIGN_OVER);
 
$status = AMProjeto::listaStatus();

$escola = $_SESSION[ambiente]->listaEscolas();
$users= $_SESSION[ambiente]->listaUsuarios();

$form->setRadioGroup("flaEstado",$status,"codStatus","desStatus");

$form->setSelect("codOrientador",$users,"codUser","nomPessoa");
$form->componentes[codOrientador]->addOption(0,$lang[sem_orientador]);

$form->setSelect("codEscola",$escola,"codEscola","nomEscola");
$form->componentes[codEscola]->addOption(0,$lang[nenhuma_escola]);
 
$proj = new AMProjeto($_REQUEST[frm_codProjeto]);

$form->loadDataFromObject($proj);

$form->setDesign(WFORMEL_DESIGN_STRING_DEFINED);
$a = "<td valign= align=rigth class=\"comum\">";
$b = "</td></tr><tr>";

$str = "$a {LABEL_FRM_DESTITULO} {FORM_EL_FRM_DESTITULO} $b";
$str.= "$a {LABEL_FRM_DESPROJETO} {FORM_EL_FRM_DESPROJETO} $b";//{TIP_FRM_DESPROJETO}</td>";
$str.= "$a {LABEL_FRM_CODORIENTADOR} {FORM_EL_FRM_CODORIENTADOR} $b";// &nbsp; </td>";
$str.= "$a {LABEL_FRM_CODESCOLA} {FORM_EL_FRM_CODESCOLA} $b";// &nbsp; </td>";
$str.="<TD COLSPAN=2 ALIGN=RIGHT>{FORM_EL_SUBMIT_BUTTONS}</TD></TR>";

$form->setDesignString($str,1);


$pag->add("<table><tr><td class=\"tdgreen\">");
$pag->add($form);
$pag->add("</td></tr></table>");


$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();

?>