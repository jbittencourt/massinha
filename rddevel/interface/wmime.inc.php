<?

include_once("$rootpath/etc/mime_drivers.inc.php");

/**
 * Classe que implementa o gerenciamento de documentos pelo padrao MIME
 * Classe que implementa o gerenciamento de documentos pelo padrao MIME
 * @author Maicon Browers
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 * @see RDPagObj
 */
class WMime extends RDPagObj {
  var $mime_type,$driver;

  function WMime($mime_type) {
    global $path,$mime_drivers;

    $this->mime_type = $mime_type;

    // acha o driver

    $this->driver='';
    if(!empty($mime_drivers['rd'])) {
      foreach($mime_drivers['rd'] as $driver) {
	if(in_array($mime_type,$driver[handles])) {
	  $this->driver = $driver;
	  return 1;
	}
      }
    }
    
    return 0;
  }


  function hasDriver() {
    return !empty($this->driver);
  }

  function setData($file_data) {
    $this->data = $file_data;
  }


  function cleanUp() {
    global $config_ini;

    $dst_temp = $config_ini[Diretorios][pathtemp];


    $dir = opendir($dst_temp);
    readdir($dir);
    readdir($dir);

    while($file = readdir($dir)) {
      $file = $dst_temp.$file;
      if(is_file($file)) {
	$time = filectime($file);
	$delta = time()-$time;
	if($delta>300) unlink($file);
      }
    }

  }


  function imprime() {
    global $config_ini;

    $dst_temp = $config_ini[Diretorios][pathtemp];

    $this->cleanUp();

    $tmpfsource = tempnam ("/tmp", "mime2html_");
    $tmpfdst = tempnam ($dst_temp,"mime2html_").".html";

    
    if(!$this->driver[inline]) {
      $handle = fopen($tmpfsource, "w");
      fwrite($handle, $this->data);
      fclose($handle);

      $tr["<source>"] = $tmpfsource;
      $tr["<dst>"] = basename($tmpfdst);
      $tr["<dstdir>"] = dirname($tmpfdst);
    
      $cmd = strtr($this->driver[location],$tr);
      $ret = exec($cmd);
    }
    else {
      $handle = fopen($tmpfdst, "w");
      fwrite($handle, $this->data);
      fclose($handle);
    }

    $this->addScript("window.location = '".$config_ini[Internet][urltemp]."/".basename($tmpfdst)."';");

    parent::imprime();
    unlink($tmpfsource);

  }

}

?>
