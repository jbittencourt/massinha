<?php

class AEBox extends RDPagObj {
  
  var $borderBox=0, $cellspacing=0, $cellpading=0;
  var $title, $type, $linhas = array(), $colunas;
  var $class = "comum", $aling = "justify", $htmlItem = "&raquo;";
  var $backgound;

  function AEBox(){
    
  }
  function SetBackGroundClass($class){
    $this->background = $class;
  }
  function SetClass($class){
    $this->class = $class;
  }

  function SetItemAlign($align){
    $this->align = $align;
  }

  function SetHtmlItem($htmlItem){
    if($htmlItem == "e" or $htmlItem == "l"){
      $this->htmlItem = "&laquo;";
    }else{
      $this->htmlItem = $htmlItem;
    }
  }
    
  function addItem($item,$link="") {
    if (is_array($item)) {
      foreach ($item as $k=>$it) {
	if ($k == "0") {
	  $this->itens[] = "<b>$it</b>";
	}
	else {
	  $add = "<&raquo; $it[0]";
	  if(!empty($it[1])) {
	    $add = "<a href=\"$it[1]\" class=\"$this->class\">$add</a>";
	  }
	  $this->itens[] = $add;
	}
      }
    }
    else {
      $item = "$this->htmlItem $item";
      if(!empty($link)) {
	$item = "<a href=\"$link\" class=\"$this->class\">$item</a>";
      }
      $this->itens[] = $item;
    }
  }
  

  function SetTitle($title, $link="", $type="img"){
    global $urlimagens, $urlimlang, $lang, $ulrcss;

    $this->title = $title;
    $this->type = $type;

    $this->setitle = "<tr><td valign=\"top\">";
    //   $this->setitle .= "<img src=\"$urlimagens/dot.gif\" width=\"1\" height=\"20\" border=\"0\">\n";

    if($this->type == "img"){
      if(is_array($this->title)){
	$temp = $this->addItem($link, $this->title);
      } else 
	if(!empty($link)){
	  $this->setitle .= "<a href=\"$link\">";
	  $this->setitle .= "<img src=\"".$urlimlang."/".$this->title."\">";
	  $this->setitle .= "</a>";
      } else {
	$this->setitle .= "<img src=\"".$urlimlang."/".$this->title."\">";
      }
    } else {
      $this->setitle = "<div class=\"$this->class\">$this->title</div>";
    }

    $this->setitle .= "<img src=\"$urlimagens/dot.gif\" width=\"1\" height=\"10\" border=\"0\">\n";
    $this->setitle .= "</td></tr>";

  }


  function add($linha){
    $this->linhas[] = $linha;
  }


  function imprime(){
    global $lang;    

    parent::add("<!-- Inicio do AEBox -->");

    parent::add("<table cellspacing=\"$this->cellspacing\" cellpading=\"$this->cellpading\"");
    parent::add(" border=\"$this->borderBox\" width=\"100%\" class=\"$this->background\">");

    if(!empty($this->setitle)){
      parent::add($this->setitle);
    }

    if (empty($this->linhas) and empty($this->itens)) {
      parent::add("<tr><td class=\"comum\"><i>".str_repeat("&nbsp;",3)."$lang[nenhum_item]</i></td></tr>");
    }

    if(!empty($this->linhas)) {
      parent::add("<tr><td>");
      foreach($this->linhas as $linha){
	parent::add($linha);
      }
      parent::add("</td></tr>");
    };

    if(!empty($this->itens)) {
      foreach($this->itens as $item){
	parent::add("<tr><td valign=\"top\" align=\"$this->align\" class=\"comum\">");
	parent::add($item);
	parent::add("</td></tr>");
      }
    };

    parent::add("</table>");
    parent::add("<!-- Fim do AEBox -->");

    parent::imprime();

  }

}

?>