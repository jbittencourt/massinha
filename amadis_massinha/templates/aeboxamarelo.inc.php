<?

class AEBoxAmarelo extends RDPagObj {

  function add($linha) {
    $this->linhas[] = $linha;
  }

  function setTitle($title) {
    $this->title = $title;
  }

  function imprime() {
    global $urlimagens, $urlimlang;


    parent::add("<table width=100% cellspacing=0 cellpadding=0 border=0><tr>");
    parent::add("<td colspan=3><img src=\"$urlimagens/img_cadastro_logo.jpg\"></td></tr>");

    //linha 0;
    $bg = "background=\"$urlimagens/bg_cadastro.jpg\"";
    parent::add("<tr $bg>");
    parent::add("<td $bg width=\"10\"><img width=10 src=\"$urlimagens/img_cadastro_top_01.jpg\"></td>");
    parent::add("<td $bg align=\"left\"><img src=\"$urlimagens/img_cadastro_top_02.jpg\"></td>");
    parent::add("<td $bg align=right><img src=\"$urlimagens/img_cadastro_top_03.jpg\"></td>");

    parent::add("<tr  $bg >");
    parent::add("<td  $bg width=\"10\" colspan=3><img src=\"$urlimagens/dot.gif\" height=20></td>");


    if(!empty($this->title)) {

      parent::add("<tr  $bg >");
      parent::add("<td  $bg width=\"10\"><img src=\"$urlimagens/dot.gif\"></td>");
      parent::add("<td  $bg colspan=2 align=\"left\"><img src=\"$this->title\"></td>");
      
    }

    parent::add("<tr  $bg ><td $bg colspan=3>");


    if(!empty($this->linhas)) {
      foreach($this->linhas as $ln) {
	parent::add($ln);
      }
    }

    parent::add("</td></td>");
    
    parent::add("<tr $bg>");
    parent::add("<td $bg width=\"10\"><img src=\"$urlimagens/img_cadastro_bot_01.jpg\"></td>");
    parent::add("<td $bg align=\"left\"><img src=\"$urlimagens/dot.gif\"></td>");
    parent::add("<td $bg width=\"10\" align=\"right\"><img src=\"$urlimagens/img_cadastro_bot_02.jpg\"></td>");

    parent::add("</table>");
    parent::imprime();
    

  }


}


?>