<?

/**
 */
/**
 * Classe que implementa um grupo de elementos smartform
 *
 * Esse elemente tem como objetivo alinhar lado a lado 2 ou mais elementos de um smartform.
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WFormEl
 */
class WFormElGroup extends WFormEl {
  var $components;

  function add($comp) {
    $this->components[] = $comp;
    $this->align = "center";
    $this->class = "";
  }


  function setAlign($align) {
    $this->align=$align;
  }

  function imprime() {
    
    $n = count($this->components);

    parent::add("<br><table align=$this->align><tr>");
    for($i=0;$i<$n;$i++) {
      parent::add("<td class=\"$this->class\">");
      parent::add($this->components[$i]);
      parent::add("</td>");
    };
    parent::add("</tr></table>");

    parent::imprime();
  }
}



?>