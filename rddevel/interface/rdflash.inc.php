<?

/**
 */
/**
 * Widget que insere uma referência para um arquivo flash.
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @abstract
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 * @see RDObj
 */
class RDFLash extends RDPagObj {
  var $file, $width, $height;

  function RDFlash($file,$w,$h) {

    $this->width = $w;
    $this->height = $h;
    $this->file = $file;

  }


  function getScript() {
    global $config_ini;

    $urlfile = $config_ini[Internet][urlmedia];

    $script.= "\n<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width=\"$this->width\" height=\"$this->height\">";
    $script.= "\n\t<param name=movie value=\"$urlfile$this->file\">";
    $script.= "\n\t<param name=quality value=high>";
    $script.= "\n\t<embed src=\"$urlfile$this->file\" quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" width=\"$this->width\" height=\"$this->height\">";
    $script.= "\n\t</embed>";
    $script.= "\n</object>";
      
    return $script;
  }


  function imprime() {

    $this->add($this->getScript());
    parent::imprime();
  }

};


?>