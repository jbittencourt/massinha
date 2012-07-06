<?

$sem_login = 1;
//include_once("/development/amadis/amadis/config.inc.php");
//include_once($pathlib."erro.inc.php");


//confere o numero de parametros

//if($_SERVER[argc]<2) { printf("Numero insuficiente de parametros"); return 0; };
  
$src = $_SERVER[argv][1];
$rec = $_SERVER[argv][2];
//if(!file_exists($src)) { printf"Arquivo $src não exise"); return 0; };

if (file_exists($src)) {
  chmod($src,0777);
  $cmd = "rm -rf \"$src\"";
  exec($cmd);  
}

/*printf"Erro desconhecido ao apagar o arquivo");

if($ret) {
  printf"Erro desconhecido ao apagar o arquivo"); return 0; 
};
*/


?>