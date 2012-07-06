<?php
include("../../config.inc.php");


$ui = new RDui("paginas", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);


$pag = new AEPaginas();
//$pag->setSubMenu(array("$lang[paginas_escola]" => "paginas_escola.php"),"comum");

$plat = $config_ini[Ambiente][plataforma_cod];

$letras = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");


if(empty($_REQUEST[frm_letra])) {
    if(!empty($_SESSION[AMADIS_ESCOLA][PAGINAS][LETRA])) {
        $letra_atual=$_SESSION[AMADIS_ESCOLA][PAGINAS][LETRA];
    } else
    $letra_atual = "a";
}
else {
    $letra_atual = $_REQUEST[frm_letra];
    $_SESSION[AMADIS_ESCOLA][PAGINAS][LETRA]=$letra_atual;
}

$chave[]=opVal("nomPessoa","$letra_atual%","","LIKE");
$usuarios = $_SESSION[ambiente]->listaSumarioUsuarios($chave);


$pag->add("<br>");
$tab = new AEBox();
$tab->setTitle("img_tit_lista_paginas.gif");

$str="";
foreach($letras as $letra) {
    if($letra==$letra_atual) {
        $str.=" - ".strtoupper($letra);
        continue;
    }
    $str.=" - <a href=\"$_SERVER[PHP_SELF]?frm_letra=$letra\" class=paginas>".strtoupper($letra)."</a>";
}

$tab->add ("<br><b><font class=\"comum\" size=+1>".$str."</font><br><br>");
//$outro = "";

$enable_info=0;
$enable_info = ($usuarios->numRecs<=100);
if($enable_info==0) {
    $pag->setMens(array($lang[desabilitado_info_adicionais]));
}

if (!empty($usuarios->records)) {
    foreach ($usuarios->records as $k=>$user) {
        if (strtolower(substr($user->nomPessoa, 0, 1)) != $letra_atual) continue;

        unset ($dir);
        unset ($file);
        unset ($linkPagina);
        unset ($linkDiario);
        unset ($linkInfo);

        if($enable_info) {
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
        }

        if($enable_info) {
            $projetos = $user->listaProjetos();
            $linkProjeto = $projetos->numRecs;
            if($projetos->numRecs!=0) {
                $linkProjeto = "&nbsp;&nbsp;&nbsp;<a href=\"veinfo.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[projetos])</a>";
            }
        }

        if($enable_info) {
            $diario = $user->listaDiario();

            if ($diario->numRecs != "0") {
                $linkDiario = "&nbsp;&nbsp;&nbsp;<a href=\"$urlferramentas/diario/diario.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[link_diario])</a>";
            }
        }

        $linkInfo = "&nbsp;&nbsp;&nbsp;<a href=\"veinfo.php?frm_codUser=".$user->codUser."\" class=\"paginas\">($lang[link_info])</a>";
  
        $tab->addItem($user->nomPessoa." (".$user->nomUser.")".$linkInfo.$linkDiario.$linkPagina.$linkProjeto);

    }
}
else {
    $tab->add ("<font class=\"comum\">".$lang[nenhum_usuario_letra].":&nbsp;<font style='color:red'>".strtoupper($letra_atual)."</font></font>");
}

$pag->add ($tab);
$pag->add("<br><br>");


$pag->imprime();


?>
