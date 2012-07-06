<?

include("../../config.inc.php");
include_once("$pathuserlib/amprojeto.inc.php");
include_once("$pathuserlib/amdiario.inc.php");
include_once("$pathuserlib/amdiariocoment.inc.php");
include_once("$pathtemplates/aediario.inc.php");
include_once("$pathtemplates/aepaginas.inc.php");
include_once("$pathtemplates/aenenhum.inc.php");
include_once("$pathtemplates/aebox.inc.php");
include_once("$pathtemplates/aepagebox.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");

$txt = new AMDiario($_REQUEST[frm_codTexto]);
switch($txt->tipoPai) {
 case "U":
   $user = new AMUser($txt->codPai);
   $voltar = "frm_codUser=".$txt->codPai;
   break;
 case "P":
   include_once("$pathuserlib/amprojeto.inc.php");
   $user = new AMProjeto($txt->codPai);
   $voltar = "frm_codProjeto=".$txt->codPai;
   break;
}


$ui = new RDui("usuarios", $_REQUEST[acao]);
$lang = $_SESSION[ambiente]->getLangUI($ui);

if ($_REQUEST[acao] == "A_comentario_make") {
  $new = new AMDiarioComent();
  $new->codTexto = $_REQUEST[frm_codTexto];
  $new->desTexto = $_REQUEST[frm_desTexto];
  $new->codUser = $_SESSION[usuario]->codUser;
  $new->tempo = time();

  $new->salva();

//   $not = new AMNoticia();
//   $not->codUser = $txt->codUser;
//   $not->flaLida = "0";
//   $not->desNoticia = $lang[comentario_pessoal_recebido]."&nbsp;".date("d/n/Y", $txt->tempo);
//   $not->tempo = time();
//   $not->salva();

  $mens[] = $lang[comentario_enviado];
}

if ($_REQUEST[origem] == "info") {
  $pag = new AENenhum();
  $voltar = "$urlferramentas/paginas/veinfo.php?frm_codUser=".$_REQUEST[user];
}
else {
  $pag = new AEDiario();
  $voltar = "diario.php?$voltar";
}

$pag->setSubMenu(array($lang[voltar] => $voltar), "comum");

if(!empty($mens)){
  foreach($mens as $men) {
    $pag->add("<br><font class=\"fontdiario\"><font size=+1>$men</font></font>");
  }
}

$pag->add ("<br>");

$pag->add ("<table width=\"100%\"><tr><td width=\"10%\" valign=top>&nbsp;</td><td width=\"80%\" valign=top class=comum>");

if($txt->codUser != $_SESSION[usuario]->codUser) {
  $pag->add("<table width=\"100%\" border=0");

  $pag->add("<tr><td colspan=\"3\">");
  $pag->add("<img src=\"$urlimlang/img_tit_adicionar_comentario.gif\" align=\"left\">");
  $pag->add("<div align=\"right\" class=\"fontgray\">$lang[hoje] ".date($lang[formato_data],time())."</div>");
  $pag->add("</td></tr>");

  $form = new WSmartform("AMDiarioComent","diario","comentarios.php?acao=A_comentario_make","codComent",array("codUser","codTexto","tempo"));
  $temp = &$form->componentes[desTexto];

  $temp->setCols(60);
  $temp->setRows(10);

  $form->setCancelOff();
  $form->componentes[codTexto]->setValue($txt->codTexto);

  $pag->add("<tr><td><img src=\"$urlimagens/dot.gif\" width=\"30\" height=\"1\" border=\"0\"></td>");
  $pag->add("<td>");
  $pag->add($form);
  $pag->add("</td><td><img src=\"$urlimagens/dot.gif\" width=\"30\" height=\"1\" border=\"0\"></td></tr>");

  //linha
  $pag->add("<tr><td colspan=3 background=\"$urlimagens/bg_linha_dots.gif\"><img src=\"$urlimagens/dot.gif\" width=0></td>");


  $pag->add("</table>");
}

$coments = $txt->listaComentarios();

$tab = new AEPageBox(5);
$tab->setTitle("img_tit_comentarios_diario.gif");

if ($coments->records) {
  foreach ($coments->records as $coment) {
    $pai = new AMUser($coment->codUser);
    $tab->addItem("<font class=\"fontdiario\">".$pai->nomPessoa."&nbsp;(".$pai->nomUser."),&nbsp;<i>".date("d/n/Y", $coment->tempo)."</i>&nbsp;-&nbsp;</font><font class=\"comum\">".$coment->desTexto."</font><br><br>");
  }
}
else $tab->add ("<font class=\"comum\"><i>$lang[nenhum_item]</i></font>");


$pag->add ($tab);
$pag->add ("<br>");

$pag->add ("</td><td width=\"10%\" valign=top>&nbsp;</td></tr></table>");
$pag->imprime();

?>
