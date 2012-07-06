<?

include_once("$rdpath/interface/rdpagobj.inc.php");


/* Classe que implementa o menu auxiliar
*
*  Classe que implementa o menu auxiliar do tipo linha azul
*
* @author Rafaello Perotto <rafaello@brturbo.com.br>
* @access public
* @version 0.5
* @package Amadis
* @see RDPagObj
*/
class AMMenuAuxiliar extends RDPagObj {
  /** @var array $itens Itens do menu.
   */
  var $itens;
  var $printHR = 1;

  function AMMenuAuxiliar($itens) {
    $this->itens = $itens;

  }

  function setPrintHR($boolPrintHR) {
    $this->printHR = $boolPrintHR;
  }

  function imprime() {
    global $pag, $urlimagens, $url;

    $texto  = "\n <!- Inicio do menu Auxiliar> ";
    $texto .= "<TABLE width=\"100%\" class=\"regular\">\n";
    $texto .= "<TR>\n";
//    $texto .= "<TD align=\"right\">\n";
    $texto .= "<TD align=\"left\">\n";

    foreach($this->itens as $text=>$link) {        
      $temp .= "<a target=\"_top\" class=\"regular\" href=\"".$link."\"><font size=\"2\">".$text."</font></a>&nbsp;&nbsp;|&nbsp;&nbsp;";
    }

    if ($pag->ativaMenuLateral != "1") {
      $texto .= "<img src=\"$urlimagens/space.gif\" height=20 width=10><br>\n";
    }

    $temp2 = substr($temp, 0, strlen($temp) - 25)."\n";
    $texto .= $temp2;
    $texto .= "</TD>\n";
    $texto .= "</TR>\n";
    if ($this->printHR) {
      $texto .= "<TR>\n";
      $texto .= "<TD><hr></TD>\n";
      $texto .= "</TR>\n";
    }
    $texto .= "</TABLE>\n";
    $texto .= "<!- Fim do Menu Auxiliar>";

    parent::add($texto);
    parent::imprime();

  }
  

}



?> 