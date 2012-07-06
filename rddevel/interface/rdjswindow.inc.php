<?
/**
 */
/**
 * Widget que insere o script para um janela pop-up
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @abstract
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 * @see RDObj
 */
class RDJSWindow extends RDPagObj {
  var $link, $title, $width, $height;
  var  $location, $status, $toolbar, $scrolling, $scrollbars;

  function RDJSWindow($link, $titulo, $w=200, $h=400) {
    $this->link = $link;
    $this->title = $titulo;
    $this->width = $w;
    $this->height = $h;

    $this->location = "no";
    $this->status = "no";
    $this->toolbar = "no";
    $this->scrolling = "yes";
    $this->scrollbars = "yes";
    $this->resize = "yes";
  }


  function getScript() {
    $texto = "handle = window.open('$this->link','$this->title','width=$this->width,";
    $texto .= "height=$this->height,resizable=$this->resize,status=$this->status,location=$this->location,";
    $texto .= "scrolling=$this->scrolling,toolbar=$this->toolbar,scrollbars=$this->scrollbars');";
    $texto .= "handle.opener = self;";
    return $texto;
  }


  function imprime() {
    
    $this->addScript($this->getScript());
    parent::imprime();
  }

};


?>
