<?

include_once("$rdpath/smartform/wformel.inc.php");
include_once("$rdpath/smartform/wradio.inc.php");

/** Define o desenho dos radios como um sobre o outro
 * @const WRADIO_DESIGN_OVER
 */
define(WRADIO_DESIGN_OVER,0);

/** Define o desenho dos radios como um ao lado do outro
 * @const WRADIO_DESIGN_SIDE
 */
define(WRADIO_DESIGN_SIDE,1);


/**
 * Classe que implementa um conjunto de RadioButtons
 *
 * Classe que implementa um conjunto de Radios
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WRadioGroup extends WFormEl {
  /**
   * @var array $radios Array contendos os radio buttons criandos
   * @var int $radioDesign Flag que define como os radios devem serem impressos um sobre o outro ou um ao lado do outro
   */
  var $radios,$radioDesign;

  function WRadioGroup($name,$label="") {
    $this->setName($name);
    $this->radios = array();
  }


  function addOption($value,$label) {
    $this->radios[$value] =  new WRadio($this->nome,$value,$label);
  }

  function parseOptionsFromList($list,$fieldValue,$fieldLabel) {

    foreach ($list->records as $obj) {
      $this->addOption($obj->$fieldValue,$obj->$fieldLabel);
    }

  }

  function imprime() {
    parent::add("<!- Inicio do RadioGroup $this->nome >");

    if($this->fdesign != WFORMEL_DESIGN_STRING_DEFINED) parent::add($this->label);
    if($this->design==WFORMEL_DESIGN_OVER) parent::add("<br>");
 
    foreach ($this->radios as $value=>$radio) {
 
      if($this->prop[value] == $value ) $radio->check();
      
      parent::add($radio);
      
      if($this->radioDesign==WRADIO_DESIGN_OVER) parent::add("\n<br>");
      
    }

    parent::add("<!- Fim do RadioGroup $this->nome >");
    parent::imprime();

  }


}



?>