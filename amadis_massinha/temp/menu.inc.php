<?php


class mainMenu extends RDPagObj 
{
    var $itensPrinc,$selectPrinc,$lateral;

    function menuPrinc($itens, $select) {
        $this->itensPrinc = $itens;
        $this->selectPrinc = $select;
    }

    function menuLateral($select) {
        $this->lateral = $select;
    }
    
    function imprime() {
        
    // desenha o CURSO ID    
        global $urltema, $lang, $url;
        $this->add ("<TABLE background=\"".$urltema."pattern4.gif\" border=0 cellPadding=0 cellSpacing=0 width=\"100%\">");
        $this->add ("<tbody><TR>");
    //$this->add ("<TD background=\"".$urltema."pattern1.gif\" width=\"50%\" valign=\"top\" align=\"left\" rowspan=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;<img border=0 src=\"".$urltema."logo.gif\"></TD>");
        $this->add ("<TD background=\"".$urltema."pattern1.gif\" width=\"50%\" valign=\"top\" align=\"left\" rowspan=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;<font size=6 color=white>AMADIS</font></TD>");
        $this->add ("<TD background=\"".$urltema."pattern1.gif\" width=\"50%\" valign=\"top\" align=\"right\"><img border=\"0\" src=\"".$urltema."junction_top.gif\" width=\"21\" height=\"21\"></TD>");
        $this->add ("<TD nowrap><font size=\"1\">&nbsp;&nbsp;<a class=\"black\" target=\"_top\" href=\"$url/ferramentas/busca/busca_site.php\">$lang[busca_no_site]</a>&nbsp;&nbsp;&nbsp;</font></TD>");
        $this->add ("<TD><img src=\"".$urltema."separator1.gif\" width=\"3\" height=\"21\"></TD><TD nowrap><font size=\"1\">&nbsp;&nbsp;<a class=\"black\" target=\"_top\" href=\"$url/ferramentas/mapa/mapa.php\">$lang[mapa_do_site]</a>&nbsp;&nbsp;&nbsp;</font></TD>");
        $this->add ("<TD><img src=\"".$urltema."separator1.gif\" width=\"3\" height=\"21\"></TD><TD nowrap><font size=\"1\">&nbsp;&nbsp;<a class=\"black\" target=\"_top\" href=\"$url/ferramentas/email/faleconosco.php\">$lang[fale_conosco]</a>&nbsp;&nbsp;&nbsp;</font></TD>");

        $this->add ("<TD><img src=\"".$urltema."separator1.gif\" width=\"3\" height=\"21\"></TD><TD nowrap><font size=\"1\">&nbsp;&nbsp;<a class=\"black\" target=\"_top\" href=\"$url/creditos.php\">$lang[creditos]</a>&nbsp;&nbsp;&nbsp;</font></TD>");

        if ($_SESSION[usuario]) {
            $this->add ("<TD><img src=\"".$urltema."separator1.gif\" width=\"3\" height=\"21\"></TD><TD nowrap><font size=\"1\">&nbsp;&nbsp;<a class=\"black\" target=\"_top\" href=\"$url/ferramentas/admin/cadastro.php?pagina=1\">$lang[dados_pessoais]</a>&nbsp;&nbsp;&nbsp;</font></TD>");
        }

        if ($_SESSION[usuario]) {
            $this->add ("<TD><img src=\"".$urltema."separator1.gif\" width=\"3\" height=\"21\"></TD><TD nowrap><font size=\"1\">&nbsp;&nbsp;<a class=\"black\" target=\"_top\" href=\"$url/index.php?acao=logoff\">$lang[logout]</a>&nbsp;&nbsp;&nbsp;</font></TD>");
        }
        $this->add ("</TR>");

        $this->add ("<tr><td vAlign=top align=right width=\"50%\" background=\"".$urltema."pattern2.gif\" colspan=12 height=22>&nbsp;</td></tr>");
        $this->add ("<tr><td background=\"".$urltema."blueline.gif\" colSpan=11><img height=4 src=\"\" width=1></td></tr></table>");

    //desenha o Main Menu
        $this->add ("<TABLE background=\"".$urltema."pattern3.gif\" border=0 cellPadding=0 cellSpacing=0 width=\"100%\">");
        $this->add ("<TR>");

        foreach ($this->itensPrinc as $k=>$itemPrinc) {
            if (strtolower($itemPrinc[0]) == strtolower($this->selectPrinc)) {
                $this->add ("<TD background=\"".$urltema."pattern13.gif\">");
                $this->add ("<img src=\"".$urltema."separator2.gif\" width=\"2\" height=\"30\"></TD>");
                $this->add ("<TD background=\"".$urltema."pattern13.gif\" class=\"mainMenu\">");
                $this->add ("<font size=\"2\">&nbsp;&nbsp;<a href=\"".$itemPrinc[1]."\" target=\"_top\" class=\"mainMenuLink\">".$itemPrinc[0]."</a>&nbsp;&nbsp;</font></TD>");
            }
            else {
                $this->add ("<TD>");
                $this->add ("<img src=\"".$urltema."separator2.gif\" width=\"2\" height=\"30\"></TD>");
                $this->add ("<TD class=\"MainMenu\">");
                $this->add ("<font size=\"2\">&nbsp;&nbsp;<a href=\"".$itemPrinc[1]."\" target=\"_top\" class=\"mainMenuLink\">".$itemPrinc[0]."</a>&nbsp;&nbsp;</font></TD>");
            }
        }

        if ($_SESSION[usuario]->nomUser != "") {
            $this->add("<td><img height=30 src=\"".$urltema."separator2.gif\" width=2></td><td class=\"welcome\" width=\"100%\" align=right><font size=2>".$lang[bem_vindo]."&nbsp;".$_SESSION[usuario]->nomUser."</font><img height=10 src=\"".$urltema."space.gif\" width=10><font size=2>&nbsp;</font></td></tr></tbody></table>");
        }
        else {
            $this->add("<td><img height=30 src=\"".$urltema."separator2.gif\" width=2></td><td class=\"welcome\" width=\"100%\" align=right><font size=2>".$lang[bem_vindo]."&nbsp;".$lang[visitante]."</font><img height=10 src=\"".$urltema."space.gif\" width=10><font size=2>&nbsp;</font></td></tr></tbody></table>");
        }

        $this->add ("</TR>");
        $this->add ("</TABLE>");


    //fecha o final ou deixa aberto, dependendo se tem ou nao menu lateral
        if ($this->lateral != "1") {
            $this->add ("<TABLE border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
            $this->add ("<TR>");
            $this->add ("</TR>");

            $this->add ("<TR bgcolor=\"ffffff\">");
            $this->add ("<TD width=\"3%\" valign=\"top\" align=\"center\" bgcolor=\"ffffff\"></TD>");
            $this->add ("<TD width=\"94%\" valign=\"top\" align=\"center\" bgcolor=\"ffffff\">");

        }

        if ($this->lateral == "1") {
            $this->add ("<TABLE border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
            $this->add ("<TR>");
            $this->add ("<td width=\"100%\" height=25></td>");
            $this->add ("<TD valign=\"top\" align=\"right\" width=30 height=25><img src=\"".$urltema."junction.gif\" height=25 width=30></TD>");
            $this->add ("<TD background=\"".$urltema."pattern14.gif\" width=\"100%\" height=25 valign=\"middle\" nowrap>");

        }

        parent::imprime();
    }
}


function menuAuxiliar($links) {

    $texto  = "\n <!- Inicio do menu Auxiliar> ";
    $texto .= "<TABLE width=\"100%\" class=\"regular\">\n";
    $texto .= "<TR>\n";
//  $texto .= "<TD align=\"right\">\n";
    $texto .= "<TD align=\"left\">\n";

    foreach($links as $link) {
        $temp .= "<a class=\"regular\" href=\"".$link[link]."\"><font size=\"2\">".$link[texto]."</font></a>&nbsp;&nbsp;|&nbsp;&nbsp;";
    }

    $temp2 = substr($temp, 0, strlen($temp) - 25)."\n";
    $texto .= $temp2;
    $texto .= "</TD>\n";
    $texto .= "</TR>\n";
    $texto .= "<TR>\n";
    $texto .= "<TD><hr></TD>\n";
    $texto .= "</TR>\n";
    $texto .= "</TABLE>\n";
    $texto .= "<!- Fim do Menu Auxiliar>";
    return ($texto);
}


?>