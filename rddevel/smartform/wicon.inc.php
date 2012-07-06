<?

define(WICON_DESIGN_OVER,0);
define(WICON_DESIGN_SIDE,1);

class WIcon extends RDPagObj {
  var $label,$icon,$action, $design;

  function WIcon($str,$img,$action) {
    $this->label = $str;
    $this->icon = $img;
    $this->action = $action;

    if(empty($_SESSION[rddevel][smartform][wicon])) {
      $_SESSION[rddevel][smartform][wicon] = 0;
    }

    $this->mode = WICON_DESIGN_OVER;
  }

  function setDesign($mode) {
    $this->design = $mode;
  }
  
  function setStyle($style,$type="label") {
    $this->style[$type] = $style;
  }

  function setNoLink() {
    $this->nolink =1;
  }


  function imprime() {
    $js = "";
    $ref = $this->action;


    if(!$this->nolink) {
      $icon_rel = "<a href=\"$ref\" $js><img src=\"$this->icon\" border=0></a>";
      $label_rel = "<a href=\"$ref\" $js class=\"$this->style[label]\">$this->label</a>";
    }
    else {
      $icon_rel = "<img src=\"$this->icon\" border=0>";
      $label_rel = "$this->label";
    }

    $this->add("<TABLE CLASS=\"".$this->style[table]."\">");

    switch($this->design) {
    case WICON_DESIGN_OVER:
      $this->add("<TR><TD ALIGN=center>$icon_rel</TD></TR>");
      $this->add("<TR><TD ALIGN=center>$label_rel</TD></TR>");
      break;
    }

    $this->add("</TABLE>");

    parent::imprime();

  }
}


?>