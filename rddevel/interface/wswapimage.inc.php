<?


include_once("$rdpath/interface/wimage.inc.php");

class WSwapImage extends WImage {
  var $link;

  function WSwapImage($link,$estado1,$estado2) {
    $this->preLoadImage($estado2);
    $this->estado = array($estado1,$estado2);
    $this->link = $link;

  }


  function imprime() {
    global $RD_DEVEL_GLOBAL;

    $count = $RD_DEVEL_GLOBAL[smartform][wswapimage]++;
    $e1 = $this->estado[0];
    $e2 = $this->estado[1];

    if(!empty($this->onClick)) {
      $onclick = " onClick=\"".$this->onClick."\" ";
    }
    
    parent::add("<a href=\"$this->link\" border=0 onMouseOut=\"RD_swapImgRestore()\" $onclick");
    parent::add(" onMouseOver=\"RD_swapImage('img_$count','','$e2',1)\">");
    parent::add("<img src=\"$e1\" name=\"img_$count\" border=0>");
    
    parent::add("</a>");
    parent::imprime();
  }


}


?>