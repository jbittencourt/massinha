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

$pag->add("<br><table border=0 cellpadding=0 cellspacing=0 width=\"100%\">");

$pag->add("<tr><td width=50% class=\"fontgray\"");
$pag->add(" valign=\"top\">");


if (isset($_REQUEST[acao])) {

  switch($_REQUEST[acao]) {
  case "A_groupescola":
    include_once("$rdpath/smartform/wselectgroup.inc.php");

    $pag = new RDPagina();

    $users = $_SESSION[ambiente]->listaUsuariosEscola($_REQUEST[frm_codEscola]);
    $group_escola = new WSelectGroup("codEscola","frm_codUsers_source",$escolas,$escolas,"codEscola","nomEscola");
    $pag->addScript($group_escola->getChangeGroupScript($users,"codUser","nomPessoa"));
    $pag->imprime();
    die();

    break;
  case "A_groupturma":
    include_once("$rdpath/smartform/wselectgroup.inc.php");

    $pag = new RDPagina();


    if($_REQUEST[frm_codEscola]) {
      $escola = new AMEscola($_REQUEST[frm_codEscola]);

      $turmas = $escola->listaTurmas();
      
      $group_escola = new WSelectGroup("codEscola","frm_codTurma",$escolas,$escolas,"codEscola","nomEscola");

      $pag->addScript($group_escola->getChangeGroupScript($turmas,"codTurma","nomTurma"));
    }
    else if($_REQUEST[frm_codTurma]) {
      include_once("$pathuserlib/amturma.inc.php");

      $turma = new AMTurma($_REQUEST[frm_codTurma]);
      $users = $turma->listaUsers();

      $group_escola = new WSelectGroup("codEscola","frm_codUsers_source",$escolas,$escolas,"codEscola","nomEscola");
      $pag->addScript($group_escola->getChangeGroupScript($users,"codUser","nomPessoa"));      
    }


    $pag->imprime();
    die();
    break;


  case "A_equipe_make":

    $proj = new AMProjeto($_REQUEST[frm_codProjeto]);
    $equipe  = $proj->listaMatriculasEquipe();


    if(!empty($equipe->records)) {
      foreach($equipe->records as $k=>$membro) {
    $ret = array_search($membro->codUser,$_REQUEST[frm_codUsers]);
    if(($ret===0) || ($ret>0)) {
      unset($_REQUEST[frm_codUsers][$ret]);
    }
    else {
      $equipe->records[$k]->deleta();
      unset($_REQUEST[frm_codUsers][$ret]);
    }
      }
    }

    foreach($_REQUEST[frm_codUsers] as $codUser) {
      $matr = new AMProjetoMatricula();
      $matr->codUser = $codUser;
      $matr->codProjeto = $proj->codProjeto;
      $matr->tempo = time();
      $matr->salva();
    }

    $pag->add("<br><p><div class=\"comum\" align=\"center\"><font size=\"2\">$lang[equipe_alterada]</font><br><br>");
    $pag->add("<a class=\"projeto\" href=\"$urlferramentas/projetos/editarprojeto.php?");
    $pag->add("frm_codProjeto=$proj->codProjeto\">&laquo;$lang[voltar_projeto]</a></div>");
    $pag->imprime();

    die();
  }
};


//tabela pra organizacao das colunas
//coluna 1
$pag->add ("<table border=0 width=\"100%\" cellpadding=0 cellspacing=0>");
$pag->add ("<tr><td width=\"10%\" valign=top>&nbsp;<p>&nbsp;</td>");
$pag->add ("<td width=\"80%\" valign=top>");
  
$tab = new AEBox();

$tab->add("<b><br><div class=fontgray>$lang[escolha_equipe]<br></div></b>");

$proj = new AMProjeto($_REQUEST[frm_codProjeto]);

$campos = array("codUser","nomPessoa","codEscola");
$equipe  = $proj->listaEquipe();

if($config_ini[Ambiente][group_by_escola]) {
  if(!$config_ini[Ambiente][classify_users_by_class]) {
    $users = $_SESSION[ambiente]->listaUsuariosEscola($proj->codEscola);
  }
  else {
    if(empty($proj->codEscola)) {
      $proj->codEscola = $_SESSION[usuario]->codEscola;
    }
    $escola = new AMEscola($proj->codEscola);
    $turmas = $escola->listaTurmas();
    $turma = $turmas->records[0];
    $users = $turma->listaUsers();
  }
}
else {
  $users = $_SESSION[ambiente]->listaUsuarios($campos);
}


$form = new WSmartForm("","equipe_make",$PHP_SELF."?acao=A_equipe_make&frm_codProjeto=$proj->codProjeto");
$form->setCancelUrl("$urlferramentas/projetos/editarprojeto.php?frm_codProjeto=$proj->codProjeto");
//elimina as duplicatas da primeira lista

$lista = new WListAdd("frm_codUsers",$users,$equipe,"codUser","nomPessoa");

if($config_ini[Ambiente][group_by_escola]) {
  include_once("$rdpath/smartform/wselectgroup.inc.php");

  if(!$config_ini[Ambiente][classify_users_by_class]) {
    $escolas = $_SESSION[ambiente]->listaEscolas();

    $acao = $_SERVER[PHP_SELF]."?acao=A_groupescola&";
    $group_escola = new WSelectGroup("codEscola",$lista->name,$acao,$escolas,"codEscola","nomEscola");
    $form->addComponent("codEscola",$group_escola);
  }
  else {
    $escolas = $_SESSION[ambiente]->listaEscolas();

    $acao = $_SERVER[PHP_SELF]."?acao=A_groupturma&";
    $group_escola = new WSelectGroup("codEscola","frm_codTurma",$acao,$escolas,"codEscola","nomEscola");

    $turmas = $escolas->records[0]->listaTurmas();

    $group_turma = new WSelectGroup("frm_codTurma",$lista->name,$acao,$turmas,"codTurma","nomTurma");

    $form->addComponent("codEscola",$group_escola);
    $form->addComponent("codTurma",$group_turma);

  }
}


$form->addComponent("codAreas",$lista);

$pag->add("<table><tr><td class=\"tdgreen\">");
$pag->add($tab);
$pag->add($form);
$pag->add("</td></tr></table>");


$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();

?>