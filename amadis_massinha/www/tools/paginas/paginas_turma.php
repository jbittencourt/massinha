<?

include("../../config.inc.php");
include_once("$pathtemplates/aepaginas.inc.php");
include_once("$pathtemplates/aebox.inc.php");
include_once("$pathuserlib/amprojeto.inc.php");
include_once("$pathuserlib/amescola.inc.php");
include_once("$pathuserlib/amturma.inc.php");
include_once("$pathuserlib/amuserturma.inc.php");


$ui = new RDui("paginas", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AEPaginas();

$itens = array();

if (!empty($_REQUEST[turma])) {
  $itens["$lang[selecionar_outra_turma]"] = "paginas_turma.php?escola=".$_REQUEST[escola];
  //$pag->setSubMenu(array("$lang[selecionar_outra_turma]" => "paginas_turma.php?escola=".$_REQUEST[escola]), "comum");
}
else {
  $itens["$lang[todos_users_escola]"] = "paginas_escola.php?escola=".$_REQUEST[escola];
  //$pag->setSubMenu(array("$lang[todos_users_escola]" => "paginas_escola.php?escola=".$_REQUEST[escola]), "comum");
}

$itens[selecionar_outra_escola] = "paginas_escola.php";

$pag->setSubMenu($itens, "comum");

$escola = new AMEscola($_REQUEST[escola]);

$pag->add ("<br>");
$tab = new AEBox();

if (empty($_REQUEST[turma])) {
  $turmas = $escola->listaTurmas();

  $pag->add ("<br><font class=\"comum\">$lang[selecione_uma_turma]:</font><br><br>");
  //$tab->setTitle("img_tit_lista_paginas.gif");

  if (!empty($turmas->records)) {
    foreach ($turmas->records as $turma) {
      $tab->addItem($turma->nomTurma, "paginas_turma.php?escola=".$escola->codEscola."&turma=".$turma->codTurma);
    }
  }
  else {
    $tab->add ("<font class=\"comum\">$lang[nenhuma_turma]</font>");
  }
}

else {
  $turma = new AMTurma($_REQUEST[turma]);
  $usuarios = $turma->listaUsers();

  $pag->add("<br>");
  $pag->add ("<font class=\"comum\">$lang[lista_usuarios_turma]: ".$turma->nomTurma."</font><br><br>");

  //$tab->setTitle("img_tit_lista_paginas.gif");

  if (!empty($usuarios->records)) {
    foreach ($usuarios->records as $k=>$user) {

      unset ($dir);
      unset ($file);
      unset ($linkPagina);
      unset ($linkDiario);
      unset ($linkInfo);
       
      $dir = @opendir("$pathpaginas/user_".$user->codUser);
      if ($dir == true) {
	$file = readdir($dir);
	$file = readdir($dir);
	$file = readdir($dir);
	if ($file !== false) {
	  $linkPagina = "&nbsp;&nbsp;<a href=\"vepagina.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[pagina])</a>";
	}
	closedir($dir);
      }
 
      $diario = $user->listaDiario();
      if ($diario->numRecs != "0") {
	$linkDiario = "&nbsp;&nbsp;&nbsp;<a href=\"$urlferramentas/diario/diario.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[link_diario])</a>";
      }

      $linkInfo = "&nbsp;&nbsp;&nbsp;<a href=\"veinfo.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[link_info])</a>";
   
      $tab->addItem($user->nomPessoa." (".$user->nomUser.")".$linkInfo.$linkDiario.$linkPagina);
                                                               
    }
  }
  else {
    $tab->add ("<font class=\"comum\">".$lang[nenhum_user_turma]."</font>");
  }
}

$pag->add ($tab);
$pag->add("<br>");

$pag->imprime();

?>
