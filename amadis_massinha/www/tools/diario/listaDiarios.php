<?php
include("../../config.inc.php");

$ui = new RDui("diario");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$submenu[$lang['ve_meu_diario']] = $urlferramentas . "/diario/diario.php";

$pag = new AEDiario();
$pag->setSubMenu($submenu,"comum");

$tab = new AEBox();

$str = '<form action="listaDiarios.php">';
$str.= '<input type=hidden name="acao" value="search">';
$str.= "<label class=comum for=search>$lang[search_diario]</label><input id=search type=text name=frm_search value='$_REQUEST[frm_search]'>";
$str.= '<form>';

$tab->add($str);

//$chave[]=opVal("nomPessoa","$letra_atual%","","LIKE");
if(!empty($_REQUEST['frm_search'])) {
    $chave[]=opVal("nomPessoa","%$_REQUEST[frm_search]%","","LIKE");
}
$usuarios = $_SESSION['ambiente']->listaSumarioUsuarios($chave);

$tab->add('<br/><br/>');

if (!empty($usuarios->records)) {
    foreach ($usuarios->records as $k=>$user) {
        unset ($linkDiario);

        $linkInfo = "&nbsp;&nbsp;&nbsp;<a href=\"$urlferramentas/paginas/veinfo.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[link_info])</a>";
        
        $diario = $user->listaDiario();

        if ($diario->numRecs != "0") {
           $linkDiario = "&nbsp;&nbsp;<a href=\"$urlferramentas/diario/diario.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[link_diario])</a>";
        } else {
            $linkDiario = "&nbsp;&nbsp;<font class='fontgray'>($lang[no_posts])</font>";
        }
  
        $tab->addItem($user->nomPessoa." (".$user->nomUser.")" . $linkInfo . $linkDiario);
    }
} else {
    if(empty($_REQUEST['frm_search'])) {
	    $tab->add("<font class=\"comum\">$lang[nenhum_diario]</font>");
    } else {
        $tab->add("<br/><font class=\"comum\">$lang[nenhum_usuario] <font style='color:red'>$_REQUEST[frm_search]</font></font>");
    }
}

$pag->add($tab);

$pag->imprime();

?>