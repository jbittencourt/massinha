<?

/**
 * Widget que implementa um botão de dicas.
 *
 * Permite que o projetista da interface adicione botões de dicas a um smartform. Usualmente o smartform tenta
 * detectar os labels dos campos no arquivo de linguagem. Caso exista no arquivo .lang um nome de campo com o sufixo
 * _desc (<i>description</i>), o smartform cria automaticamente um tip com esse string.
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WTip extends RDPagObj {

  function WTip($texto) {
    $this->texto = $texto;
    $this->requires("tip.js.php");
  }

  function imprime() {
    global $urlimagens,$smartform,$RD_DEVEL_GLOBAL,$config_ini;

    $img = $urlimagens."/".$config_ini[SmartForm][icon_info];
    $num = $RD_DEVEL_GLOBAL[smartform][wtip][count];
    if(empty($num)) $num = 0;

    $this->addScript("messages[$num] = new Array('','$this->texto',\"#FFFFFF\");");
    $this->add("<a href=\"#\" onmouseover=\"doTooltip(event,$num)\" onmouseout=\"hideTip()\"><img src=\"$img\" border=0 ></a>");

    $RD_DEVEL_GLOBAL[smartform][wtip][count] = $num+1;

    if($RD_DEVEL_GLOBAL[smartform][wtip][div]!=true) {
      $RD_DEVEL_GLOBAL[pag_end][]  = "<div id=\"tipDiv\" style=\"position:absolute; visibility:hidden; z-index:0\"></div>";
      $RD_DEVEL_GLOBAL[pag_end][] = "<script>initTip();</script>";

      $RD_DEVEL_GLOBAL[smartform][wtip][div]=true;
    };
      
    parent::imprime();
  }

}


?>