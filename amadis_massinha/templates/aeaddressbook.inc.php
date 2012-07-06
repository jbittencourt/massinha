<?


class  AEAddressBook extends RDPagina {
  var $contents;

  function  AEAddressBook() {
    $this->requires("amadis_escola.css","CSS");
    $this->requires("addressbook.js");
  }

  function add($line) {
    $this->contents[] = $line;
  }


  function imprime() {
    global $urlimagens;

    $this->setMargin(0,0,0,0);

    parent::add("<table borde=1 width=\"100%\" cellpadding=0 cellspacing=0>");
    parent::add("<tr><td valign=right><img src=\"$urlimagens/img_contatos_01.jpg\">");
    parent::add("</table>");

    if(!empty($this->contents)) {
      foreach($this->contents as $line) {
	parent::add($line);
      }
    }

    parent::imprime();

  }


}



?>