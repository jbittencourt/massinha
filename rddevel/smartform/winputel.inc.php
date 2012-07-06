<?

/**
 * Classe que implementa um Elemento do tipo input
 *
 * Classe que implementa um Elemento do tipo input
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WFormEl
 */
class WInputEl extends WFormEl {
  /** 
   * @var int $labelAfter Determina que o label deve ser impresso após o input e não antes dele
   */
    var $design;
    var	$showLabel = true;

    function WInputEl($name,$value,$type="") {
        $this->setName($name);
        $this->setValue($value);
        $this->setType($type);
    }


    function setValue($value) {
        $this->value = $value;
        $this->prop[value] = $value;
    }

    function setType($tipo) {
        $this->prop[type] = $tipo;
    }
    
    function setNoLabel() {
        $this->showLabel = false;
    }
    
    function imprime() {

    //desenha o label e o elemento em colunas separadas
        if ($this->design == WFORMEL_DESIGN_LEFT_TWO_COLS) {
            $str.= $this->label."</TD><TD>";
        }
        else {
            if(($this->design!=WFORMEL_DESIGN_SIDE_RIGTH) &&
            ($this->design!=WFORMEL_DESIGN_STRING_DEFINED)) {
                if($this->showLabel) {
                    $str = $this->label."&nbsp;";
                }
            }
        }

        if($this->design==WFORMEL_DESIGN_OVER) {
            $str.="<BR>";
        }
        
        $str.= "<INPUT ";

        foreach ($this->prop as $prop=>$valor) {
            if(isset($valor)) {
                $str.= $prop."=\"".$valor."\" ";
            }
            else {
                $str.= " $prop ";
            }
        }
        $str.= ">";
        if($this->showLabel) {
            if($this->design==WFORMEL_DESIGN_SIDE_RIGTH) $str.= $this->label."&nbsp;";
        }

        $this->add($str);
        parent::imprime();

    }
    
}

?>
