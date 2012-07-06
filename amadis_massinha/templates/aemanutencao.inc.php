<?php

define("AE_MANUT_DB_OUT",1);
define("AE_MANUT_ADMIN",2);


class AEManutencao extends RDPagina 
{
    var $motivo;


    function  AEManutencao($motivo=0) {
        $this->motivo = $motivo;
        $this->requires("amadis_escola.css","CSS");
    }

    function imprime() {
        global $urlimagens, $urlimlang, $lang;

        parent::add("<p><br><br>");

        parent::add("<table cellpadding=\"0\" cellspacing=\"0\" width=\"344\" align=\"center\" border=\"0\" bordercolor=\"Blue\">");
        parent::add("<tbody>");
        parent::add("<tr bgcolor=\"#ffffff\">");
        parent::add("<td colspan=\"3\">");
        parent::add("<img src=\"$urlimagens/img_logo_amadis_manutencao.gif\" width=\"200\" height=\"56\">");
        parent::add("</td>");
        parent::add("</tr>");

        parent::add("<tr>");
        parent::add("<td bgcolor=\"#ffffff\"><img src=\"$urlimagens/dot.gif\" width=\"32\" height=\"10\"> </td>");
        parent::add("<td><img src=\"$urlimlang/img_manutencao01.gif\" width=\"168\" height=\"34\"></td>");
        parent::add("<td><img src=\"$urlimagens/img_manutencao02.gif\" width=\"234\" height=\"34\"></td>");
        parent::add("</tr>");

        parent::add("<tr>");
        parent::add("<td bgcolor=\"#ffffff\">&nbsp; </td>");
        parent::add("<td background=\"$urlimagens/img_manutencao03.gif\" valign=\"top\" style=\"padding-left: 20px; padding-top: 10px;\" class=\"fontgray\">");
        parent::add("<p>$lang[manutencao]<br>");
        parent::add("<br>");

        parent::add("<a href=\"#\" class=\"mnlateral\">$lang[tentar_novamente]</a></p>");
        parent::add("</td>");

        parent::add("<td height=\"33\"><img src=\"$urlimagens/img_manutencao04.gif\" width=\"234\" height=\"108\"></td>");
        parent::add("</tr>");

        parent::add("<tr>");
        parent::add("<td bgcolor=\"#ffffff\">&nbsp;</td>");
        parent::add("<td><img src=\"$urlimagens/img_manutencao05.gif\" width=\"168\" height=\"61\"></td>");
        parent::add("<td><img src=\"$urlimagens/img_manutencao06.gif\" width=\"234\" height=\"61\"></td>");
        parent::add("</tr>");

        parent::add("</tbody>");
        parent::add("</table>");

        if(!empty($this->motivo)) {
            switch($this->motivo) {
                case AE_MANUT_DB_OUT:
                    $temp = $lang[motivo_db_foradoar];
                    break;
                case AE_MANUT_ADMIN:
                    $temp = $lang[motivo_admin];
                    break;

            }
            parent::add("<p><div class=\"error\">$lang[motivo]:&nbsp; $temp<br></div>");
        }


        parent::imprime();
    }

}


