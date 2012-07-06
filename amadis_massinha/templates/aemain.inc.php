<?php

class AEMain extends RDPagina
{
    var $main_menu;
    var $contents;
    var $tema;
    var $slidein;
    var $navmenu;

    function AEMain() {
        global $lang, $url, $urlimlang, $urlferramentas;

        $this->setTitle($lang[nome_ambiente]);
        $this->requires("amadis_escola.css","CSS");


        $this->main_menu = new AEMainMenu();

        $this->main_menu->addItem("$url/inicial.php",
        array("$urlimlang/mn_bt_inicial_off.gif",
				    "$urlimlang/mn_bt_inicial_on.gif",)
        );

        $this->main_menu->addItem("$urlferramentas/diario/diario.php",
        array("$urlimlang/mn_bt_diario_off.gif",
				    "$urlimlang/mn_bt_diario_on.gif",)
        );

        $this->main_menu->addItem("$urlferramentas/email/email.php",
        array("$urlimlang/mn_bt_correio_off.gif",
				    "$urlimlang/mn_bt_correio_on.gif",)
        );

        $this->main_menu->addItem("$urlferramentas/paginas/paginas.php",
        array("$urlimlang/mn_bt_paginas_off.gif",
				    "$urlimlang/mn_bt_paginas_on.gif",)
        );

        $this->main_menu->addItem("$urlferramentas/chat/chat.php",
        array("$urlimlang/mn_bt_chat_off.gif",
				    "$urlimlang/mn_bt_chat_on.gif",)
        );

        $this->main_menu->addItem("$urlferramentas/projetos/projetos.php",
        array("$urlimlang/mn_bt_projetos_off.gif",
				    "$urlimlang/mn_bt_projetos_on.gif",)
        );

        $this->main_menu->addItem("$urlferramentas/forum/forum.php",
        array("$urlimlang/mn_bt_foruns_off.gif",
				    "$urlimlang/mn_bt_foruns_on.gif",)
        );

        $this->slidein = new WSlidInMenu(170,160);
        $this->navmenu = new AENavMenu();

        $this->leftMargin = 163;
    }


    function openNavMenu() {
        $this->slidein->setMode(SLIDEINMENU_MODE_OPEN);
        $this->navmenu->locked = 1;
    }
    
    function add($line) {
        $this->contents[] = $line;
    }


    function setMens($mens) {
        $this->mens = $mens;
    }

    function setMenuSuperior($img1,$img2,$img3) {
        $this->tema[0]= $img1;
        $this->tema[1]= $img2;
        $this->tema[2]= $img3;
    }

    function setImgId($img) {
        $this->imgid = $img;
    }

    function setLeftMargin($w) {
        $this->leftMargin = $w;
    }
    

    function addLine() {
        global $urlimagens, $urlimlang;
        $this->add("</td><tr><td></td><td background=\"$urlimagens/bg_linha_dots.gif\"><img src=\"$urlimagens/dot.gif\" width=1 heigth=1></td></tr>");
        $this->add("<tr><td><img src=\"$urlimagens/dot.gif\"  width=20></td><td>");
    }


    function setSubMenu($itens,$class) {
        if(!is_array($itens)) {
            die("setSubMenu $itens deve ser um array");
        };

        $this->submenuitens = $itens;
        $this->submenuclass = $class;

    }

    function imprime() {
        global $urlimagens, $urlimlang, $lang, $config_ini,$url;


        if(empty($this->tema)) {
            die("AEMain: Voc� presica definir um tema para utilizar AEMain");
        }

        $this->setMargin(0,0,0,0);
        $this->setBgImage($this->tema[0]);

        $this->slidein->setRevealSize(30);
        $this->slidein->add($this->navmenu);
        parent::add($this->slidein);



    //linha superio em branco
        parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/dot.gif\" width=\"18\" height=\"10\" border=\"0\"></td>");
        parent::add("<td><img src=\"$urlimagens/dot.gif\" width=\"44\" height=\"1\"  border=\"0\"></td>");
        parent::add("<td><img src=\"$urlimagens/dot.gif\" width=\"2\"  height=\"1\" border=\"0\"></td>");
        parent::add("<td><img src=\"$urlimagens/dot.gif\" width=\"510\" height=\"1\" border=\"0\"></td>");
        parent::add("</tr>");

    //linha do logo do amadis e ilustra��o com massinha
        parent::add("<tr>");
        parent::add("<td colspan=\"3\" width=103><img src=\"$urlimagens/img_logo_amadis.gif\" width=\"203\"  height=\"45\"  border=\"0\"><br>");
        parent::add("<img src=\"$urlimagens/img_barra_logo.gif\" width=\"203\" height=\"13\" border=\"0\"></td>");


    //<!-- area ilustracao -->
        parent::add("</td><td rowspan=\"2\">");
        parent::add("<img src=\"".$this->tema[1]."\" width=\"510\" height=\"113\" border=\"0\"></td>");
        parent::add("</tr>");


    //nome de logon do usu�rio
        $nomuser = "Ciclano";
        if(!empty($_SESSION[usuario])) {
            $nomuser = $_SESSION[usuario]->nomUser;
        }

        parent::add("<tr>");
        parent::add("<td bgcolor=\"#f2f2f2\"><img src=\"$urlimagens/img_cabeca_robo.gif\" border=\"0\"></td>");
        parent::add("<td bgcolor=\"#f2f2f2\" align=\"left\" valign=\"middle\" class=\"fontorange\"><font class=\"fontred\"><b>$nomuser</b></font><br>$lang[bem_vindo]<br></td>");
        parent::add("<td bgcolor=\"#f2f2f2\"><img src=\"$urlimagens/dot.gif\" height=\"55\" width=2 border=\"0\"></td>");
        parent::add("</tr>");



        parent::add("<tr>");
        parent::add("<td colspan=\"4\" align=\"left\">");
        parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">");
        parent::add("<tbody>");
        parent::add("<tr>");
        parent::add("<td  valign=top>");

    // <!-- barra fechar -->
        parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">");
        parent::add("<tbody>");
        parent::add("<tr>");
        parent::add("<td><img src=\"$urlimagens/img_bar_sair_01.gif\" width=\"49\" height=\"34\" border=\"0\"></td>");
        parent::add("<td><img src=\"$urlimagens/img_bar_sair_02.gif\" width=\"62\" height=\"9\"  border=\"0\"><br>");
        parent::add("<a href=\"$url/index.php?acao=A_logout\"><img src=\"$urlimlang/img_bt_sair.gif\" width=\"62\" height=\"19\" border=\"0\"></a><br>");
        parent::add("<img src=\"$urlimagens/img_bar_sair_03.gif\" width=\"62\" height=\"6\" border=\"0\"></td>");
        parent::add("<td><img src=\"$urlimagens/img_bar_sair_04.gif\" width=\"51\" height=\"34\" border=\"0\"></td>");
        parent::add("</tr>");

        parent::add("</tbody>");
        parent::add("</table>");
        parent::add("</td>");


        parent::add("<td>");
        parent::add($this->main_menu);
        parent::add("</td>");

        parent::add("<td  valign=top><img src=\"".$this->tema[2]."\" width=\"24\" height=\"34\" border=\"0\"></td>");

        parent::add("</table></table>")
        ;
        parent::add("<table width=100% cellpadding=0 cellspacing=0 border=0>");
        parent::add("<tr><td width=\"".$this->leftMargin."\"><img src=\"$urlimagens/dot.gif\" width=\"".$this->leftMargin."\"></td><td>");

        parent::add("<table width=\"530\" cols=3  border=0 cellpadding=0 cellspacing=0 style=\"layout:fixed\"> ");

        if(!empty($this->imgid)) {
            parent::add("<td width=20><img src=\"$urlimagens/dot.gif\" width=20 border=0 height=20></td>");

            parent::add("<tr><td colspan=\"3\" background=\"$urlimagens/bg_linha_dots.gif\"><img src=\"$urlimagens/dot.gif\" width=1></td></tr>");

            parent::add("<tr><td><img src=\"$urlimagens/dot.gif\" width=20></td>");
            parent::add("<td width=2><img src=\"$this->imgid\"></td>");

            if(empty($this->submenuitens)) {
                parent::add("<td align=left>&nbsp</td>");
            }
            else {
                $temp = array();
                foreach($this->submenuitens as $item=>$link) {
                    $temp[] = "<a href=\"$link\" class=\"$this->submenuclass\">$item</a>";
                }

                parent::add("<td align=right class=\"$this->submenuclass\">".implode(" | ",$temp)."</td>");
            }
            parent::add("</tr>");

            parent::add("<tr><td colspan=\"3\" bgcolor=\"#d2d2d2\"><img src=\"$urlimagens/dot.gif\" width=1></td></tr>");


        }
        
        parent::add("<tr><td><img src=\"$urlimagens/dot.gif\"  width=20></td><td colspan=2>");


        if(!empty($this->mens)) {
            parent::add("<br>");
            foreach($this->mens as $men) {
                parent::add("<div class=\"error\">$men</div>");
            }
        }

        if(!empty($this->contents)) {
            foreach($this->contents as $line)
            parent::add($line);
        }

        parent::add("</td></tr></table></td></tr></table><br>");

        parent::imprime();
    }

}

?>