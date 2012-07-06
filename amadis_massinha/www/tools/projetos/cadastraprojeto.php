<?php
include("../../config.inc.php");


$ui = new RDui("projetos", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AEProjeto();

$pag->add("<br><table border=0 cellpadding=0 cellspacing=0 width=\"100%\">");

$pag->add("<tr><td width=50% class=\"fontgray\"");
$pag->add(" valign=\"top\">");


if ($_REQUEST[erro] == "repetido") {
    $pag->add ("<div align=\"center\" class=\"fontred\"><b><font size=\"3\">$lang[repetido]</font></b></div><br>");
}

$box = new AEBox();
$box->SetTitle("img_tit_criar_projeto.gif");
$box->add("");
$pag->add($box);

if (isset($_REQUEST[acao])){

    switch($_REQUEST[acao]){
        case "A_criatab_pag2" :
            
            unset($chave);
            $chave[] = opval ("desTitulo", $_REQUEST[frm_desTitulo]);
            $outras = new RDLista("AMProjeto", $chave);
            if ($outras->numRecs != "0") {
                header("location: cadastraprojeto.php?erro=repetido");
            }

            global $config_ini;

            $proj = new AMProjeto();

            $proj->codOwner = $_SESSION[usuario]->codUser;
            $proj->LoadDataFromRequest();

            $proj->codPlataforma = $config_ini[Ambiente][plataforma_cod];

            /*
            $proj->desTitulo = $_REQUEST[frm_desTitulo];
            $proj->desProjeto = $_REQUEST[frm_desProjeto];
            $proj->codEscola = $_REQUEST[frm_codEscola];
            $proj->codOrientador = $_REQUEST[frm_codOrientador];
            $proj->codPlataforma = $config_ini[Ambiente][plataforma_cod];
            $proj->flaEstado = $_REQUEST[frm_flaEstado];
            */

            $proj->tempo = time();

            $_SESSION[temp][proj_cria] = $proj;

            $tab = new AEBox();
            $tab->add("<br><div class=\"fontgray\">$lang[escolha_area]</div><br>");

            $form = new WSmartForm("","select_areas",$_SERVER[PHP_SELF]."?acao=A_criatab_pag3");

            $areas = $_SESSION[ambiente]->listaAreas();
            $lista = new WListAdd("frm_codAreas",$areas,"","codArea","nomArea");

            $form->addComponent("codAreas",$lista);

            $form->setDesign(WFORMEL_DESIGN_STRING_DEFINED);
            $a = "<td valign= align=rigth class=\"comum\">";
            $b = "</td></tr><tr>";

            $str = "$a {LABEL_FRM_CODAREAS} {FORM_EL_FRM_CODAREAS} $b";

            $str.="<TD COLSPAN=2 ALIGN=RIGHT>{FORM_EL_SUBMIT_BUTTONS}</TD></TR>";

            $form->setDesignString($str,1);

            $tab->add($form);
            $pag->add("<table><tr><td class=\"tdgreen\">");
            $pag->add($tab);
            $pag->add("</td></tr></table>");

            $pag->add ("</td></tr></table>");

            $pag->imprime();

            die();

            break;

        case "A_criatab_pag3":

            if(empty($_REQUEST[frm_codAreas])) {
                Header("Location: $PHP_SELF?acao=A_criatab_pag2&tab_erro=1");
            };

            $_SESSION[temp][proj_cria_areas] = $_REQUEST[frm_codAreas];

            $tab = new AEBox();

            $tab->add("<br><div class=\"fontgray\">$lang[escolha_equipe]</div><br>");

            $form = new WSmartForm("","select_areas","$PHP_SELF?acao=A_make");


            if($config_ini[Ambiente][group_by_escola]) {
                if(!$config_ini[Ambiente][classify_users_by_class]) {
                    $users = $_SESSION[ambiente]->listaUsuariosEscola($proj->codEscola);
                }
                else {
                    $codEscola = $_SESSION[temp][proj_cria]->codEscola;
                    if(empty($codEscola)) {
                        $codEscola = $_SESSION[usuario]->codEscola;
                    }
                    $escola = new AMEscola($codEscola);
                    $turmas = $escola->listaTurmas();
                    $turma = $turmas->records[0];
                    $users = $turma->listaUsers();
                }
            }
            else {
                $users = $_SESSION[ambiente]->listaUsuariosPlataforma($config_ini[Ambiente][plataforma_cod]);
            }

            $lista = new WListAdd("frm_codUsers",$users,"","codUser","nomPessoa");


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



            $form->addComponent("codUsers",$lista);
            $tab->add($form);
            $pag->add("<table><tr><td class=\"tdgreen\">");
            $pag->add($tab);
            $pag->add("</td></tr></table>");

            $pag->add ("</td></tr></table>");

            $pag->imprime();


            die();
            break;

        case "A_make":

            $proj = $_SESSION[temp][proj_cria];
            $proj->salva();


            if($proj->novo==0){
                $proj->matricula($_SESSION[usuario]->codUser);
                if(!empty($_REQUEST[frm_codUsers])) {
                    foreach($_REQUEST[frm_codUsers] as $user) {
                        $proj->matricula($user);
                    }
                }

                foreach($_SESSION[temp][proj_cria_areas] as $area) {
                    $proj->addArea($area);
                };
            };

            $pag->add("<br><br><div class=\"fontred\" align=\"center\"><b><font size=\"3\">$lang[projeto_cadastrado]</font></b><br>");
            $pag->add("<br><div align=\"center\"><a class=\"projeto\" href=\"$urlferramentas/projetos/projeto.php?frm_codProjeto=");
            $pag->add("$proj->codProjeto\">".$lang[voltar_projeto]."</a></div>");

            $pag->add ("</td></tr></table>");

            $pag->imprime();
            die();
            break;

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

    }
};



$empty_list = array("codProjeto","codOwner","codPlataforma","tempo","hits");
$form = new WSmartForm("AMProjeto","projeto_pag2","$PHP_SELF?acao=A_criatab_pag2",$empty_list);
$form->setStructure(0);
$form->componentes[desProjeto]->fdesign = WFORMEL_DESIGN_STRING_DEFINED;
$form->forceToText("desTitulo",60,30);

$status = AMProjeto::listaStatus();
$form->setRadioGroup("flaEstado",$status,"codStatus","desStatus");
$form->componentes[flaEstado]->fdesign = WFORMEL_DESIGN_STRING_DEFINED;

$form->componentes[flaEstado]->setStyleClass("fontgray");
$campos = array("codUser","nomUser","nomPessoa");
$users = $_SESSION[ambiente]->listaUsuariosPlataforma($config_ini[Ambiente][who_plataforma_master],'',$campos);

$form->setSelect("codOrientador",$users,"codUser","nomPessoa");
$form->componentes[codOrientador]->addOption(0,$lang[sem_orientador]);


$escolas = $_SESSION[ambiente]->listaEscolas();

$form->setSelect("codEscola",$escolas,"codEscola","nomEscola");
$form->componentes[codEscola]->addOption(0,$lang[nenhuma_escola]);

$form->setDesign(WFORMEL_DESIGN_STRING_DEFINED);
$a = "<td valign= align=rigth class=\"comum\">";
$b = "</td></tr><tr>";
$br = "<BR>";

$str = "$a <b>{LABEL_FRM_DESTITULO}</b> $br {FORM_EL_FRM_DESTITULO} $b";
$str.= "$a <b>{LABEL_FRM_DESPROJETO}</b> $br {FORM_EL_FRM_DESPROJETO} {TIP_FRM_DESPROJETO} $b";
$str.= "$a <b>{LABEL_FRM_FLAESTADO}</b> $br  {FORM_EL_FRM_FLAESTADO} $b";
$str.= "$a <b>{LABEL_FRM_CODORIENTADOR}</b> $br {FORM_EL_FRM_CODORIENTADOR} $b";// &nbsp; </td>";
$str.= "$a <b>{LABEL_FRM_CODESCOLA}</b> $br {FORM_EL_FRM_CODESCOLA} $b";// &nbsp; </td>";
$str.="<TD COLSPAN=2 ALIGN=RIGHT>{FORM_EL_SUBMIT_BUTTONS}</TD></TR>";

$form->setDesignString($str,1);


$pag->add("<table><tr><td class=\"tdgreen\">");
$pag->add($form);
$pag->add("</td></tr></table>");



$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");


$pag->add("</td></tr></table>");

$pag->imprime();


?>