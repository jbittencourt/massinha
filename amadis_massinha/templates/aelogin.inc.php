<?


class AELogin extends RDPagObj{

  function imprime(){

    global $config_ini;
    global $urlimagens, $url,$lang, $$urlimlang;

    parent::add("<form action=\"$url/inicial.php\" name=\"frm_login\" method=\"post\">");

    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" width=\"344\"");
    parent::add(" align=\"center\" border=\"0\">");

    parent::add("<tr><td background=\"$urlimagens/img_login01.gif\"><img src=\"$urlimagens/dot.gif\"");
    parent::add(" width=\"1\" height=\"108\" alt=\"\" border=\"0\"></td></tr>");
    
    parent::add("<tr><td valign=\"middle\" background=\"$urlimagens/img_login02.gif\" height=\"34\"");
    parent::add(" style=\"padding-left: 90px\"><input type=\"text\" name=\"frm_login\" maxLength=50 size=15></td>");
    parent::add("</tr><tr>");
    parent::add("<td valign=\"middle\" background=\"$urlimagens/img_login03.gif\"");
    parent::add(" height=\"33\" style=\"padding-left: 90px\"><input type=\"password\"");
    parent::add(" name=\"frm_pwd\" maxLength=50 size=15></td>");
    parent::add("</tr><tr>");
    parent::add("<td valign=\"top\" background=\"$urlimagens/img_login04.gif\" height=\"74\"");
    parent::add(" style=\"padding-left: 160px\"><a href=\"javascript:document.frm_login.submit();\" ><img src=\"$urlimagens/img_entrar_login.gif\"");
    parent::add(" width=\"62\"");
    parent::add(" height=\"19\" alt=\"\" border=\"0\"></a></td>");
    parent::add("</tr></table>");

    parent::add("</form>");


    parent::imprime();
  }

}

?>