<?

include("../../config.inc.php");
include_once("$pathtemplates/aepaginas.inc.php");
include_once("$pathtemplates/aebox.inc.php");
include_once("$pathuserlib/amprojeto.inc.php");
include_once("$pathuserlib/amescola.inc.php");
include_once("$pathtemplates/aepaginas.inc.php");

$ui = new RDui("paginas", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AEPaginas();

if (!empty($_REQUEST[escola])) {
  $pag->setSubMenu(array("$lang[selecionar_outra_escola]" => "paginas_escola.php"), "comum");
}
else {
  $pag->setSubMenu(array("$lang[paginas_alfabetica]" => "paginas.php"), "comum");
}

$pag->add ("<br>");
$tab = new AEBox();

if (empty($_REQUEST[escola])) {
  $escolas = $_SESSION[ambiente]->listaEscolas();

  $pag->add ("<br><font class=\"comum\">$lang[selecione_uma_escola]:</font><br><br>");
  //$tab->setTitle("img_tit_lista_paginas.gif");

  if (!empty($escolas->records)) {
    foreach ($escolas->records as $escola) {
      if ($config_ini[Ambiente][classify_users_by_class] == "1") {
	$tab->addItem($escola->nomEscola, "paginas_turma.php?escola=".$escola->codEscola);
      }
      else {
	$tab->addItem($escola->nomEscola, "paginas_escola.php?escola=".$escola->codEscola);
      }
    }
  }
  else {
    $pag->add ("$lang[nenhuma_escola]");
  }
}

else {
  $plat = $config_ini[Ambiente][plataforma_cod];
  $escola = new AMEscola($_REQUEST[escola]);
  $usuarios = $_SESSION[ambiente]->listaUsuariosEscola($escola->codEscola, $plat);

  $pag->add("<br>");
  $pag->add ("<font class=\"comum\">$lang[lista_usuarios_escola]: ".$escola->nomEscola."</font><br><br>");

  //$tab->setTitle("img_tit_lista_paginas.gif");

  if (!empty($usuarios->records)) {
    foreach ($usuarios->records as $k=>$user) {

      unset ($dir);
      unset ($file);
      unset ($linkPagina);
      unset ($linkDiario);
      unset ($linkInfo);
      unset ($linkProjeto);
       
      $dir = @opendir("$pathpaginas/user_".$user->codUser);
      if ($dir == true) {
	$file = readdir($dir);
	$file = readdir($dir);
	$file = readdir($dir);
	if ($file !== false) {
	  $linkPagina = "&nbsp;&nbsp;<a href=\"vepagina.php?frm_codUser=".$user->codUser."\" class=\"paginas\">(Pagina)</a>";
	}
	closedir($dir);
      }


      $projetos = $user->listaProjetos();
      if($projetos->numRecs!=0) {
	$linkProjeto = "&nbsp;&nbsp;&nbsp;<a href=\"veinfo.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[projetos])</a>";
      }

 
      $diario = $user->listaDiario();
      if ($diario->numRecs != "0") {
	$linkDiario = "&nbsp;&nbsp;&nbsp;<a href=\"$urlferramentas/diario/diario.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[link_diario])</a>";
      }

      $linkInfo = "&nbsp;&nbsp;&nbsp;<a href=\"veinfo.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[link_info])</a>";
   
      $tab->addItem($user->nomPessoa." (".$user->nomUser.")".$linkInfo.$linkDiario.$linkPagina.$linkProjeto);
                                                               
    }
  }
  else {
    $pag->add ($lang[nenhum_user_escola]);
  }
}

$pag->add ($tab);
$pag->add("<br>");

$pag->imprime();

?>
