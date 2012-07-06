<?

include_once($pathlib."widgets/pagobj.inc.php");


/**
 * Widget que implementa um corretor ortográfico
 * Widget que implementa um corretor ortográfico
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @abstract
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 * @see RDPagObj
 */
class WSpellCheck extends RDPagObj {
  var $text;

  function WSpellCheck($text) {
    $language = $_SESSION[ambiente]->language;
    $this->text = $text;
  }


  function checkString() {
    $text = preg_replace('/\s/', ' ', stripslashes($this->text));
           
    $text = escapeshellcmd($text);
    
    $offset = $currentline = 0;

    $ret = array();
    
    $p = popen("echo ^$text | ispell  -a -S", "r");
    while (!feof($p)) {
      $line = fgets($p,1024);
      if (preg_match('/^(?:&|\?) (\w+) \d+ (\d+): (.+)$/', $line, $m)) {
	$wrong++;
	$temp = array("source"=>$m[1],"pos"=>$offset + $m[2],"sugestions"=>explode(", ", $m[3]));
	$ret[]=$temp;
      }
      elseif (preg_match('/^# (\w+) (\d+)/', $line, $m)) {
	$temp = array("source"=>$m[1],"pos"=>$offset + $m[2],"sugestions"=>"");
	$ret[]=$temp;
      }
    
    }
    pclose($p);
    
    return $ret;
    
  }

  
  function imprime() {

    $errors = $this->checkString();
    $texto = $this->text;

    $offset = 0;

    foreach($errors as $error) {
      $newstr = "<font color=\"#FF0000\">$error[source]</font>";
      $size = strlen($error[source]);
      $texto = substr_replace ($texto,$newstr,$error[pos]+$offset-1,$size);
      $offset += (strlen($newstr)-$size);
    };

    $this->add("<table border=0><tr><td>$texto</td></table>");
    parent::imprime();

  }

  

}



?>