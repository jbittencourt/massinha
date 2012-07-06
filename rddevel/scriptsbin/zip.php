<?
//session_start();
$sem_login = 1;

$config_ini[arquivos][default_grp_gid] = 1005;
$config_ini[arquivos][default_perm] = 0755;

if ($_SERVER[argc] < 2 ) {
  printf($_SERVER[PHP_SELF]."Numero insuficiente de parametros");
  return 0;
}

if ($_SERVER[argv][3]=="unzip") {
  //unzip
  $dirbase = escapeshellarg($_SERVER[argv][1]);
  $arquivo_zip = escapeshellarg($_SERVER[argv][4]);
  print_r($dirbase."\n");;
  //  chdir($dirbase);
  // a opcao -o garante que os arquivos sejam sobrescritos
  $comando = "unzip -o $arquivo_zip -d $dirbase ";
  exec($comando,$l);
  unlink($arquivo_zip);
}
else {
  //zip

  $dirbase = escapeshellarg($_SERVER[argv][1]);
  $sessionId = escapeshellarg($_SERVER[argv][2]);
  //muda para o diretorio base
  chdir($dirbase);

  //forma o comando zip
  //usa a funcao session_id para gerar um nome de arquivo zip unico para cada sessao
  //evitando assim que o arquivo de compactacao temporario seja sobrescrito
  $nomeArqZip = "/tmp/download".$sessionId.".zip";
  $comando = "zip -r \"".$nomeArqZip."\" ";
  for ($i=3;$i<=$_SERVER[argc]-1;$i++) {
    //se o primeiro nome do nome do arquivo for "/" entao eh um diretorio
    //e entao acrescentar o . na frente do nome para entender que eh no diretorio atual
    if ($_SERVER[argv][$i][0]=="/") {
      $_SERVER[argv][$i][0]= " ";
      $comando.= $_SERVER[argv][$i] . "/ ";   
    }
    else {
      $comando.= $_SERVER[argv][$i] . " ";  
    }
  }

  echo "comando : $comando";
  
  exec($comando,$l);
  print_r($l);
  
}


?>
