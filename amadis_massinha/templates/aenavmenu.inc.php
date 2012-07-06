<?php

class AENavMenu extends RDPagObj {


    function AENavmenu() {
    //precisa pq usa o wtreenode
        $this->requires("divs.js");
    }


    function imprime() {
        global $urlimagens, $urlimlang, $lang, $urlferramentas;


        parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#ffffff\">");
        parent::add("<tr>");
        parent::add("<td colspan=\"3\"><img src=\"$urlimagens/mnl_header.gif\" width=\"163\" height=\"14\" alt=\"\" border=\"0\"></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"18\" height=\"14\" alt=\"\" border=\"0\"></td>");
        parent::add("<td colspan=2><img src=\"$urlimlang/mnl_ioio_movel.gif\" alt=\"\" border=\"0\"></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"1\" height=\"1\" alt=\"\" border=\"0\"></td>");
        parent::add("<td valign=\"top\"><img src=\"$urlimagens/white_dot.gif\" width=\"1\" height=\"12\" border=\"0\"><br>");
    
    //<!-- links dinâmicos --> 			
        parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
        parent::add("width=\"135\">");
        parent::add("<tr><td>");

        $tree1 =  new AETree("<b>$lang[minha_pagina]</b>");
        $tree1->add("<a href=\"$urlferramentas/paginas/vepagina.php?frm_codUser=".$_SESSION[usuario]->codUser."\" class=\"mnlateral\">&raquo; $lang[ver_minha_pagina]</a>");
        $tree1->add("<br><a href=\"$urlferramentas/upload/upload.php?codUser=".$_SESSION[usuario]->codUser."\" class=\"mnlateral\">&raquo; $lang[publicar_minha_pagina]</a>");

        parent::add($tree1);

        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"1\"");
        parent::add("height=\"5\" alt=\"\" border=\"0\"></td>");
        parent::add("</tr>");

    //foreach dos projetos
        parent::add("<tr><td>");

        if(!empty($_SESSION[usuario])) {
            $projetos = $_SESSION[usuario]->listaProjetos();
            if(!empty($projetos->records)) {

                $tree = new AETree("<b>$lang[projetos]</b>");

                foreach($projetos->records as $matr) {
                    $proj = new AMProjeto($matr->codProjeto);
                    $tree->add("$br<a href=\"$urlferramentas/projetos/editarprojeto.php?frm_codProjeto=$proj->codProjeto\" class=\"mnlateral\">&raquo; $proj->desTitulo</a>");
                    $br = "<BR>";

                }
                parent::add($tree);
            }
        }
        parent::add("</td>");
        parent::add("</tr>");
        parent::add("<tr>");

        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"1\"");
        parent::add("height=\"5\" alt=\"\" border=\"0\"></td>");
        parent::add("</tr>");

        parent::add("</table>");




        parent::add("<td background=\"$urlimagens/mnl_linha_lateral.gif\"><img src=\"$urlimagens/mnl_linha_lateral.gif\" width=\"10\" height=\"30\"  border=\"0\"></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"1\" height=\"1\" alt=\"\" border=\"0\"></td>");
        parent::add("<td><img src=\"$urlimagens/mnl_linha_horizontal.gif\" width=\"135\"  height=\"22\" border=\"0\"></td>");
        parent::add("<td><img src=\"$urlimagens/mnl_linha_encontro.gif\" width=\"10\" height=\"22\" border=\"0\"></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"1\" height=\"1\" alt=\"\" border=\"0\"></td>");
        parent::add("<td valign=\"top\">");
    //<!-- links ferramentas --> 			
        parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"135\">");

        parent::add("<tr><td>");

        if(!empty($_SESSION[usuario])) {
            $tree = new AETree("<b>$lang[web_papo]</b>");

      //:$users = $_SESSION[ambiente]->getOnlineUsers();

            
            if(!empty($users->records)) {
                foreach($users->records as $user) {
                    if($user->codUser==$_SESSION[usuario]->codUser) continue;

                    $tree->add("$br <font class=\"comum\">&raquo; $user->nomUser</font>");
                    $br = "<BR>";
                    $cont++;
                }
            };


            if(empty($cont)) {
                $tree->add("<font class=\"comum\">&raquo $lang[nobody_online]</font>");
            }
            
            parent::add($tree);

        }
        parent::add("</td>");


    //contatos
        
        $win = new RDJSWindow("$urlferramentas/agenda/addressbook.php?",$lang[contatos],600,400);
        $link1 = $win->getScript();

        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"1\"
 height=\"5\" alt=\"\" border=\"0\"></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/img_seta.gif\" width=\"8\" height=\"8\" align=\"middle\" border=\"0\"><a href=\"#\" onClick=\"$link1\" class=\"mnlateral\">$lang[contatos]</a></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"1\" height=\"5\" alt=\"\" border=\"0\"></td>");
        parent::add("</tr>");


    //correio
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"1\"
 height=\"5\" alt=\"\" border=\"0\"></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/img_seta.gif\" width=\"8\" height=\"8\" align=\"middle\" border=\"0\"><a href=\"$urlferramentas/email/email.php\" class=\"mnlateral\">$lang[meu_correio]</a></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/white_dot.gif\" width=\"1\" height=\"5\" alt=\"\" border=\"0\"></td>");
        parent::add("</tr>");



		
        parent::add("</table>");
        parent::add("<img src=\"$urlimagens/white_dot.gif\" width=\"1\" height=\"12\" border=\"0\"><br>");
    //		<!-- fim links ferramentas --> 			
        parent::add("</td>");
        parent::add("<td background=\"$urlimagens/mnl_linha_lateral.gif\"><img src=\"$urlimagens/mnl_linha_lateral.gif\" width=\"10\" height=\"30\"  border=\"0\"></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/mnl_footer_01.gif\" width=\"18\" height=\"8\" alt=\"\" border=\"0\"></td>");
        parent::add("<td><img src=\"$urlimagens/mnl_footer_02.gif\" width=\"135\" height=\"8\" alt=\"\" border=\"0\"></td>");parent::add("<td><img src=\"$urlimagens/mnl_footer_03.gif\" width=\"10\" height=\"8\" alt=\"\" border=\"0\"></td>");
        parent::add("</tr>");
        parent::add("</table>");


        $lock[close] = "$urlimagens/img_taxinha_on.gif";
        $lock[open] = "$urlimagens/img_taxinha_off.gif";


        $onclick.= "if(lock_pull_menu==1) { ";
        $onclick.= "  document.lockimg.src = '".$lock[open]."';";
        $onclick.= "  lock_pull_menu = 0; ";
        $onclick.= "} else { ";
        $onclick.= "  document.lockimg.src = '".$lock[close]."';";
        $onclick.= "  lock_pull_menu = 1;";
        $onclick.= "};";

        if($this->locked) {
            parent::addScript("lock_pull_menu = 1;");
            parent::add("<a href=\"#\" onClick=\"$onclick\"><img  name=\"lockimg\" src=\"$lock[close]\"  border=0></a>");
        } else {
            parent::addScript("lock_pull_menu = 0;");
            parent::add("<a href=\"#\" onClick=\"$onclick\"><img src=\"$lock[open]\" name=\"lockimg\" border=0></a>");
        }
        
        parent::imprime();

    }

}
