<?


include_once("../../config.inc.php");
include_once("$pathtemplates/aebox.inc.php");

$ui = new RDui("procura", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new RDPagina();
$pag->requires("amadis_escola.css","CSS");
$pag->setTitle($lang[titulo_janela]);
$pag->setMargin(0,0,0,0);
$pag->requires("amadis.css.php","CSS");

if($_REQUEST[acao_pertinente]=="correio") {
  $pag->requires("addressbook.js");
}


if(!empty($_REQUEST[acao])) {
  switch($_REQUEST[acao]) {
  case "A_add_contatos":
    include_once("$pathuserlib/amcontato.inc.php");
    $sucesso = 0;
    if(!empty($_REQUEST[frm_selecteduser])) {
      foreach($_REQUEST[frm_selecteduser] as $coditem) {
	$ret = $_SESSION[usuario]->addContato($coditem);
	
	switch($ret) {
	case 0: 
	  $sucesso++;
	  break;
	case 1: 
	  $user = new AMUser($coditem);
	  $mens[] = $lang[usuario_ja_e_contato].": ".$user->nomPessoa;

	}
      }
    }
    
    if($sucesso>0) $mens[] = $lang[contato_adicionado];
    $_SESSION[contatos] = "";    
	  
    break;
    
  }
}


$escolas = $_SESSION[ambiente]->listaEscolas();
$options[] = "<option value=\"0\">$lang[todas_escolas]";
if(!empty($escolas->records)) {
  foreach($escolas->records as $escola) {
    $df="";
    if($escola->codEscola==$_REQUEST[frm_escola]) $df="selected";
    $options[]="<option value=".$escola->codEscola." $df>".$escola->nomEscola;
  }

}

$pag->add("<form name=procura action=\"".$_SERVER[PHP_SELF]."\">");
$pag->add("<input type=hidden name=acao_pertinente value=\"".$_REQUEST[acao_pertinente]."\">");

$pag->add("<table width=\"100%\" border=0 cellspacing=0 cellpaggind=0 background=\"$urlimagens/bg_barra_chat.gif\">");
$pag->add("<tr><td>$lang[username]<br><input type=text name=frm_username size=20 value=\"$_REQUEST[frm_username]\"></td>");
$pag->add("<td>$lang[turma]<br><input type=text name=frm_turma size=10 value=\"$_REQUEST[frm_turma]\"></td>");
$pag->add("<td>$lang[escola]<br><select name=frm_escola>".implode("\n",$options)."</select>");
$pag->add("<td><a href=\"javascript:document.procura.submit()\"><img src=\"$urlimagens/find_small.png\" border=0></a>");

$pag->add("<tr><td colspan=4 background=\"$urlimagens/bg_fundo_laranja.gif\" heigth=2><img src=\"$urlimagens/dot.gif\" heigth=2></td></td>");
$pag->add("<table>");
$pag->add("</form>");


if(!(empty($_REQUEST[frm_username]) && empty($_REQUEST[frm_escola]) && empty($_REQUEST[frm_turma]))) {
  $lst = $_SESSION[ambiente]->procuraUser($_REQUEST[frm_username],$_REQUEST[frm_turma],$_REQUEST[frm_escola]);
}


$pag->add("<br>");


if(!empty($mens)) {
  foreach($mens as $men) {
    $pag->add("<br><div style=\"text-align: center\" class=\"fontwarning\">$men</div>");
  }
}

$pag->add("<br>");


$pag->add("<form name=\"lista\" action=procura.php method=post>");
$pag->add("<input type=hidden name=frm_username value=\"".$_REQUEST[frm_username]."\">");
$pag->add("<input type=hidden name=frm_turma  value=\"".$_REQUEST[frm_turma]."\">");
$pag->add("<input type=hidden name=frm_escola value=\"".$_REQUEST[frm_escola]."\">");
$pag->add("<input type=hidden name=acao_pertinente value=\"".$_REQUEST[acao_pertinente]."\">");
$pag->add("<input type=hidden name=acao value=\"\">");



//$box = new AMBox(3);
//$box->setTitle($lang[usuarios]);


$pag->add("<table border=0 cellspacing=0 cellpadding=0 width=\"100%\">");
$class = "tdblue";

if(!empty($lst->records)) {
  $jsarray = array();

  $pesq_atual = "frm_username=".$_REQUEST[frm_username]."&frm_turma=".$_REQUEST[frm_turma]."frm_escola=".$_REQUEST[frm_escola];
  foreach($lst->records as $user) {
    if($class=="tdblue") { $class=""; } else { $class="tdblue"; };

    $check = "<input  type=checkbox name=\"frm_selecteduser[]\"  value=".$user->codUser.">";

    $pag->add("<tr><td class=\"$class\">$check <td class=\"$class\"> $user->nomPessoa <td class=\"$class\">$user->strEmail");

    $jsarray[$user->codUser] = array("nome"=>$user->nomPessoa,
				     "email"=>$user->strEMail);
  }

}


$pag->add("</table>");

if(!empty($jsarray)) {
  $script="nomeuser = new Array(); emailuser= new Array();";
  foreach($jsarray as $k=>$dados) {
    $script.= "nomeuser[$k] = '".$dados[nome]."'; emailuser[$k] = '".$dados[email]."'; ";
  }

  $pag->addScript($script);
}

if(!empty($lst->records)) {
  $pag->add("<p align=center>");
  $pag->add("<a class=\"comum\" href=\"javascript:document.lista.acao.value='A_add_contatos'; document.lista.submit();\">$lang[adicionar_contatos]</a>");

  if($_REQUEST[acao_pertinente]=="correio") {
    $pag->add("&nbsp;");

    $script = "for(var i=0; i<document.lista.length; i++) {";
    $script.= "  if(document.lista[i].name=='frm_selecteduser[]') {";
    $script.= "    if(document.lista[i].checked) {";
    $script.= "      var x = document.lista[i].value;";
    $script.= "      addStr(nomeuser[x]+' <'+emailuser[x]+'>');";
    $script.= "    }";
    $script.= "  }";
    $script.= "};";

    $pag->add("<a class=\"comum\" href=\"javascript:$script\">$lang[enviar_email]</a>");
  };

  $script = "for(var i=0; i<document.lista.length; i++) {";
  $script.= "  if(document.lista[i].name=='frm_selecteduser[]') {";
  $script.= "    document.lista[i].checked = true;";
  $script.= "  }";
  $script.= "};";

  $pag->add("&nbsp;<a class=\"comum\" href=\"javascript:#\" onClick=\"$script\">$lang[selecionar_todos]</a>");

}

$pag->add("</form>");


$pag->imprime();

?>