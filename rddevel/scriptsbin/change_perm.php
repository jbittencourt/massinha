<?

$sem_login = 1;
include_once("/usr/local/amadis_escola/ambiente/config.inc.php");
include_once($pathlib."erro.inc.php");



if ($_SERVER[argc] == 3) {

  $src = $_SERVER[argv][1];
  $perm = $_SERVER[argv][2]; 

  echo "src : $src\n";
  echo "perm : $perm";
 
  if (file_exists($src)) {
    chmod($src,$perm);
  }    
}

?>
