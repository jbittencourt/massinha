<?

include("../../config.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/aechat.inc.php");
include_once("$pathtemplates/aebox.inc.php");

include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathuserlib/amchatsala.inc.php");

include_once("$rdpath/interface/rdjswindow.inc.php");


$ui = new RDui("chat", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);


$pag = new AEChat();


if(isset($_REQUEST[acao])) {
  switch($_REQUEST[acao]) {
  case "A_chatroom_make":
    $new = new AMChatSala();
    $new->loadDataFromRequest();
    $new->tipoPai = "G";
    $new->codPlataforma =$config_ini[Ambiente][plataforma_cod];
    $new->datFim = $new->datInicio+$config_ini[Chat][room_timeout];
    $new->tempo = time();
    $new->salva();
    break;
  case "A_salas_encerradas":
    include_once("$pathtemplates/aepagebox.inc.php");

    $itens[$lang[salas_abertas]] = $_SERVER[PHP_SELF];
    $pag->setSubMenu($itens,"comum");
    
    $tab = new AEPageBox(15);
    $tab->setTitle("img_tit_sala_andamento.gif");

    $salas = $_SESSION[ambiente]->listaSalasChatGerais(1);

    if(!empty($salas->records)) {
      foreach($salas->records as $sala) {
	$win = new RDJSWindow("chatroom.php?frm_codSala=$sala->codSala","Chat",560,420);
	$date = date("h:i ".$lang[formato_data],$sala->tempo);
	$tab->addItem("<a href=\"#\" onClick=\"".$win->getScript()."\" class=\"comum\">$sala->nomSala</a> ($date) -  $sala->desSala");
      }
    }

    $pag->add("<br>");
    $pag->add($tab);

    $pag->imprime();
    die();

    break;
  }
}


$itens[$lang[salas_encerradas]] = $_SERVER[PHP_SELF]."?acao=A_salas_encerradas";
$pag->setSubMenu($itens,"comum");


$pag->add("<br><img src=\"$urlimlang/img_tit_criar_sala.gif\">");

//$hidden = "flaPermanente";

$form1 = new WSmartform("RDChatRoom","criarchat","$_SERVER[PHP_SELF]?acao=A_chatroom_make",array("codSala","tempo","datFim","codPlataforma"),array("datInicio"));

$form1->spacing =1;
$form1->setCancelOff();
$form1->setDesign(WFORMEL_DESIGN_SIDE);
$form1->setLabelClass("fontchat");

$form1->componentes[flaPermanente]->setValue(1);
$form1->componentes[desSala]->prop[size]=30;
$form1->componentes[datInicio]->setValue(time());

$form1->submitgroup_align = "right";
$form1->submit_label = $lang[criar_sala];

$pag->add($form1);

$pag->addLine();

$tab = new AEBox();
$tab->setTitle("img_tit_sala_andamento.gif");

$salas_abertas = $_SESSION[ambiente]-> listaSalasChatGerais();

if(!empty($salas_abertas->records)) {
  foreach($salas_abertas->records as $sala) {
    $win = new RDJSWindow("chatroom.php?frm_codSala=$sala->codSala","Chat",560,420);
    $tab->addItem("<a href=\"#\" onClick=\"".$win->getScript()."\" class=\"comum\">$sala->nomSala</a> - $sala->desSala");
  }
}

$pag->add("<br>");
$pag->add($tab);

$pag->imprime();



?>