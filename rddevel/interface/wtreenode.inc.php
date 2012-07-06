<?



class WTreeNode extends RDPagObj {
  /**
   * @var array $itens Items que foram adicionados a árvore
   */
  var $itens;
  var $images;
  var $name;
  var $display;
  var $open;
  var $caption;
  var $ident;

  function WTreeNode($caption) {
    global $RD_DEVEL_GLOBAL;


    $this->requires("divs.js");

    $this->caption = $caption;
    $this->name = "treenode_".(++$RD_DEVEL_GLOBAL[wtreenode][nodecount]);

    $this->display = "none";
    $this->ident = "10px";
  }


  function add($item) {
    $this->itens[] = $item;
  }


  function setBullets($im1,$im2) {
    $this->images[close] = $im1;
    $this->images[open] = $im2;
  }


  function setClasses($link,$div) {
    $this->classes[link] = $link;
    $this->classes[div] = $div;

  }

  function propagateDesign() {
    if(!empty($this->itens)) {
      $i=0;
      foreach($this->itens as $item) {
	if(is_subclass_of($item,"wtreenode") || is_a($item,"wtreenode")) {
	  $node = &$this->itens[$i];
	  $node->setClasses($this->classes[link],$this->classes[div]);
	  $node->setBullets($this->images[close],$this->images[open]);
	  $node->setIdentDistance($this->ident);
	  $node->propagateDesign();
	};
	   
	$i++;
      }
    }

  }

  function setIdentDistance($w) {
    $this->ident =  $w;
  }

  function imprime() {

    
    if(empty($this->open)) $this->open = "0";


    parent::addScript($this->name."_open = $this->open;");

    if(!(empty($this->images[close]) || empty($this->images[open]))) {
      //coloca a imagem
      if(!$this->open) {
	parent::add("<img name=\"".$this->name."_img\" src=\"".$this->images[close]."\">");
      } else {
	parent::add("<img name=\"".$this->name."_img\" src=\"".$this->images[open]."\">");
      };
    }

    $onclick = "toggle('$this->name');";

    $v_open = $this->name."_open";
    $v_img = $this->name."_img";

    if(!empty($this->images)) {
      $onclick.= "if(!$v_open) {";
      $onclick.= "  document.$v_img.src = '".$this->images[close]."';";
      $onclick.= "} else {";
      $onclick.= "  document.$v_img.src = '".$this->images[open]."';";
      $onclick.= "}";
    }

    if(is_a($this->caption,"wimage")) {
      $this->caption->setOnClick($onclick);
      parent::add($this->caption);
    } 
    else {
      parent::add("<a href=\"#\" onClick=\"$onclick\" class=\"".$this->classes[link]."\">");
      //caption can be a text or a rdpagobj. But be carfull. Just simple rdpagobj
      //will work
      parent::add($this->caption);
      parent::add("</a>");
    }


    if($this->open) {
      $this->display = "visible";
    }

    parent::add("<DIV name=\"".$this->name."\" id=\"".$this->name."\" class=\"".$this->classes[div]."\" style=\"display: $this->display\">");
   

    parent::add("<table border=0 cellpading=0 cellspacing=0><tr>");
    parent::add("<td style=\" width: $this->ident\" >&nbsp;</td><td>");

    if(!empty($this->itens)) {
      foreach($this->itens as $item) {
	parent::add($item);
      }
    }

    parent::add("</td></tr></table>");
    parent::add("</DIV>");
    parent::imprime();
     
  }
  

}


?>