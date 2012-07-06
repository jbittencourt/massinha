<?

define("SLIDEINMENU_MODE_CLOSED",0);
define("SLIDEINMENU_MODE_OPEN",1);

class WSlidInMenu extends RDPagObj {
  /**
   * @var array $content Content for custom add
   */
  var $content;
  var $width,$top;

  function WSlidInMenu($width,$top) {
    $this->requires("slide_in_menu.js");
    $this->width= $width;
    $this->top = $top;

    $this->reveal = 12;
  }


  function setMode($mode) {
    $this->mode = $mode;
  }

  function setRevealSize($tam) {
    $this->reveal = $tam;
  }

  function add($line) {
    $this->content[] = $line;
  }

  function imprime() {
    global $urlimagens;

    $w = $this->width;
    $t = $this->top;
    $r = $this->reveal;
    
    
    parent::add("\n <!-- Slide In Menu Start -->");

    if($this->mode==SLIDEINMENU_MODE_OPEN) {
      $pos = 0;
    }
    else {
      $pos = ($w-$r) * -1;
    }

    parent::add("<div id=\"slidemenubar2\" style=\"position:absolute; left: $pos; top:$t; width:$w;\" onMouseover=\"pull()\" onMouseout=\"draw()\">");

    
   

    /* codigo para fazer para ns4
     document.write('<style>\n#slidemenubar{\nwidth:'+slidemenu_width+';}\n<\/style>\n')
     document.write('<layer id="slidemenubar" left=0 top='+slidemenu_top+' width='+slidemenu_width+' onMouseover="pull()" onMouseout="draw()" visibility=hide>')
    */

    if(!empty($this->content)) {
      foreach($this->content as $linha) {
	parent::add($linha);
      }
    }

    parent::add("</div>");
    parent::add("\n <!-- Slide In Menu End -->");

    parent::addScript("slide_in_init($w,$t,$r);");

    parent::imprime();


  }

}



?>