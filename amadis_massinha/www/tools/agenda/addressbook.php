<?
include_once("../../config.inc.php");
include_once("$pathuserlib/amcontato.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/aeaddressbook.inc.php");
include_once("$pathtemplates/aebox.inc.php");
include_once("$rdpath/interface/rdjswindow.inc.php");

$ui = new RDui("addressbook", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);


class Form extends WSmartForm {

  function Form($obj="") {

    if (is_object($obj)) {
      $action = $_SERVER[PHP_SELF]."?acao=A_altera_make";
    }
    else { 
      $action = $_SERVER[PHP_SELF]."?acao=A_inclui_make";
    }


    $campos_ausentes = array("tempo");
    $campos_hidden = array("codContato");

    $this->WSmartForm("AMContato","form1",$action,$campos_ausentes,$campos_hidden);

    if (is_object($obj)) 
      $this->loadDataFromObject($obj);

    $labels = array("codContato"=>"","codUser"=>"","nomPessoa"=>"","strEmail"=>"","tempo"=>"",""=>"");
    $this->loadLabels($labels);
  }

}

switch($_REQUEST[acao]) {
 case "A_inclui":
   $pag = new RDPagina();
   $form = new Form();
   $pag->add($form);
   $pag->imprime();
   die();
   break;

 case "A_inclui_make":
   $obj = new AMContato();
   $obj->loadDataFromRequest();
   $obj->salva();
   header("Location: ".$_SERVER[PHP_SELF]);
   break;

 case "A_altera":
   $pag = new RDPagina();
   $chaves = array();
   $chaves[] = opVal("codContato",$_REQUEST[frm_codContato]);
   $obj = new AMContato($chaves);
   $form = new Form($obj);
   $pag->add($form);
   $pag->imprime();
   die();
   break;

 case "A_altera_make":
   $chaves = array();
   $chaves[] = opVal("codContato",$_REQUEST[frm_codContato]);
   $obj = new AMContato($chaves);
   $obj->loadDataFromRequest();
   $obj->salva();
   header("Location: ".$_SERVER[PHP_SELF]);
   break;

 case "A_del_make":
   if(!empty($_REQUEST[frm_selecteduser])) {
     foreach($_REQUEST[frm_selecteduser] as $item) {
       $cont = new AMContato($item);
       if(!$cont->novo) {
	 $cont->deleta();
	 $_SESSION[contatos] = "";
       }
     }
   }

   header("Location: ".$_SERVER[PHP_SELF]);

   break;

}

$pag =  new AEAddressBook();
//$pag = new RDPagina();
$pag->setTitle($lang[addressbook]);



$lista = $_SESSION[usuario]->listaContatos();
$pag->add("<p><form name=lista action=addressbook.php method=post>");
$pag->add("<input type=hidden name=acao value=\"\">");
$pag->add("<input type=hidden name=acao_pertinente value=\"$_REQUEST[acao_pertinente]\">");

   

//$box->setRowTitle(array("",$lang[nomPessoa],$lang[email]));

$pag->add("$lang[contatos]");

$pag->add("<table border=0 cellspacing=0 cellpadding=0 width=\"100%\">");
$class = "tdblue";




if(!empty($lista->records)) {

  foreach($lista->records as $obj) {
    if(empty($obj->codUser)) {
      $nomPessoa = $obj->nomPessoa;
      $email = $obj->strEMail;
    }
    else {
      $user = new AMUser($obj->codUser);
      $nomPessoa = $user->nomPessoa;
      $email = $user->strEMail;
    }

    if($class=="tdblue") { $class=""; } else { $class="tdblue"; };

    // 	 $line = "<a href=\"#\" onClick=\"addStr('$nomPessoa <$email>')\" class=comum>$nomPessoa <i>($email)<i></a>";
       
    $chkbox = "<input type=checkbox name=\"frm_selecteduser[]\" value=".$obj->codContato.">";
    $pag->add("<tr><td class=\"$class\">$chkbox <td class=\"$class\"> $nomPessoa <td class=\"$class\">$email");
    $jsarray[$obj->codContato] = array("nome"=>$nomPessoa,
				       "email"=>$email);
  }

  if(!empty($jsarray)) {
    $script="nomeuser = new Array(); emailuser= new Array();";
    foreach($jsarray as $k=>$dados) {
      $script.= "nomeuser[$k] = '".$dados[nome]."'; emailuser[$k] = '".$dados[email]."'; ";
    }

    $pag->addScript($script);
  }


}

$pag->add("</table>");

$pag->add("<p align=center>");
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

$pag->add("&nbsp;<a class=\"comum\" href=\"javascript:document.lista.acao.value='A_del_make'; document.lista.submit();\" >$lang[apagar_contato]</a>");

$win = new RDJSWindow("procura.php",$lang[procura],600,400);
$link = $win->getScript();

$pag->add("&nbsp<a href=\"#\" class=\"comum\" onClick=\"javascript:$link\">$lang[procura_amigo]</a>");



$pag->add("</form>");

$pag->imprime();



?>
