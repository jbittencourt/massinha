<?

$sem_login=1;
include("config.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/aeboxamarelo.inc.php");



$ui = new RDui("cadastro", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);


$pag = new RDPagina();
$pag->requires("amadis_escola.css","CSS");



$pag->add("<table width=100% border=0><tr>");
$pag->add("<td colspan=3><img src=\"$urlimagens/dot.gif\" width=100></td>");


if(!empty($_REQUEST[acao])) {

  switch($_REQUEST[acao]) {
  case "A_make":
    if (!$user) {
      $nomeuser = $_SESSION[ambiente]->user_by_username($_REQUEST[frm_nomUser]);
      if ($nomeuser->novo != "2") {
	$erros[] = $lang[ja_cadastrado];
      }

      $nomepessoa = $_SESSION[ambiente]->user_by_name($_REQUEST[frm_nomPessoa]);
      if ($nomepessoa->novo != "2") {
	$erros[] = $lang[ja_cadastrado_nome];
      }

    }

    if($_REQUEST[frm_desSenha]!=$_REQUEST[frm_desSenhaConfirma]) {
      $erros[] = $lang[senhas_nao_conferem];
    }


    if ($_REQUEST[frm_nomUser] == "") {
      $erros[] = $lang[username_em_branco];
    }

    //verifica se os caracteres existem no nomUser
    $valid_string = "[a-z0-9]+([_\\.-][a-z0-9]+)*";
    if (!eregi("^$valid_string$",$_REQUEST[frm_nomUser])) {
      $tmp = eregi_replace($valid_string, "<font color=blue>\\0</font>", $_REQUEST[frm_nomUser]);
      $erros[] = "$lang[caracteres_estranhos]: <font color=red>$tmp</font>";
    }

    $user = new AMUser();
    $user->loadDataFromRequest();

    if(empty($erros)) {
      $user->homedir = 1;
      $user->flaAprovado =1;
      $user->tempo = time();
      //note ($user);
      $user->salva();

      if(!$user->novo) {
	$pag->add ("<font class=\"comum\"><center>$lang[cadastro_pessoal_efetuado]<br><br><br>
                    <form method=post name=login action=\"$url/inicial.php\">
                <input type=hidden name=\"frm_login\" value=\"".$_REQUEST[frm_nomUser]->nomUser."\">
            <input type=hidden name=\"frm_pwd\" value=\"".$_REQUEST[frm_desSenha]."\">
            <input type=hidden name=\"acao\" value=\"A_login\">
            <p align=center><input type=submit value=\"$lang[ir_meu_webfolio]\">");

	$pag->imprime();
	die();
      }
    }
    break;
  }
}



if(!empty($erros)) {
  foreach($erros as $erro) {
    if(empty($erro)) continue;
    $pag->add("<tr><td><img src=\"$urlimagens/dot.gif\"  height=30 width=100></td>");
    $pag->add("<td align=center><h3><font color=red>$erro</font></h3>");
    $pag->add("<td><img src=\"$urlimagens/dot.gif\" height=30 width=100></td>");
  }
}


$pag->add("<tr><td><img src=\"$urlimagens/dot.gif\" width=100></td>");
$pag->add("<td>");


$box = new AEBoxAmarelo();
$box->setTitle("$urlimlang/img_cadastro_tit.gif");

$form = new WSmartform("AMUser","cadastro",$_SERVER[PHP_SELF]);
$form->setDesign(WFORMEL_DESIGN_STRING_DEFINED);

if(!empty($user)) {
     $form->loadDataFromObject($user);
}


$confirma = new WText("frm_desSenhaConfirma","","60","60");
$confirma->setSize(40);
$form->addComponent("desSenhaConfirma",$confirma);



$form->setDate("datNascimento","d/m/Y");
$form->componentes[datNascimento]->setCalendarOn();

$escolas = $_SESSION[ambiente]->listaEscolas();

$form->setSelect("codEscola",$escolas,"codEscola","nomEscola");
$form->componentes[codEscola]->addOption(0,$lang[nenhuma_escola]);


$a = "<tr><td valign=base width=\"30%\" align=right class=\"comum\">";
$b = "</td><td valign=base width=\"70%\">";

$str = "$a {LABEL_FRM_NOMPESSOA} $b {FORM_EL_FRM_NOMPESSOA} &nbsp; {TIP_FRM_NOMPESSOA}</td>";
$str.= "$a {LABEL_FRM_NOMUSER} $b {FORM_EL_FRM_NOMUSER} &nbsp; {TIP_FRM_NOMUSER}</td>";
$str.= "$a {LABEL_FRM_DESSENHA} $b {FORM_EL_FRM_DESSENHA} &nbsp; {TIP_FRM_DESSENHA}</td>";
$str.= "$a {LABEL_FRM_DESSENHACONFIRMA} $b {FORM_EL_FRM_DESSENHACONFIRMA} &nbsp; </td>";
$str.= "$a {LABEL_FRM_DATNASCIMENTO} $b {FORM_EL_FRM_DATNASCIMENTO} &nbsp; </td>";
$str.="<TR><TD COLSPAN=2 ALIGN=CENTER>{FORM_EL_SUBMIT_BUTTONS}</TD>";

$form->setDesignString($str,1);
$form->addComponent("acao",new WHidden("acao","A_make"));

//seta o tamnho dos campos
$form->componentes[desSenha]->prop[size]=40;

$form->componentes[nomPessoa]->prop[tabindex]=1;
$form->componentes[nomUser]->prop[tabindex]=2;
$form->componentes[desSenha]->prop[tabindex]=3;
$form->componentes[desSenhaConfirma]->prop[tabindex]=4;
$box->add($form);

$pag->add($box);

$pag->add("<td><img src=\"$urlimagens/dot.gif\" width=100></td>");
$pag->add("</table>");

$pag->imprime();

?>