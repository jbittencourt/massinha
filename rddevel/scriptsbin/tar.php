<?

//tar -czf teste.tar.gz mmqG17.cpp p5ar.doc

//tar -zxvf arquivo.tar.gz

$sem_login = 1;

if ($_SERVER[argc] < 2 ) {
  logError($_SERVER[PHP_SELF],"Numero insuficiente de parametros");
  return 0;
}

$dirbase = $_SERVER[argv][1];
$sessionId = $_SERVER[argv][2];
chdir($dirbase);  //muda para o diretorio base

if ($_SERVER[argv][3]=="untar") {
  //descompacta
  $arquivo_tar = $_SERVER[argv][4];
  $comando = "tar -zxvf \"$arquivo_tar\"";
  echo $comando;
  exec($comando);
}
else {
  //compacta

  //forma o comando tar
  //usa a funcao session_id para gerar um nome de arquivo tar unico para cada sessao
  //evitando assim que o arquivo de compactacao temporario seja sobrescrito

  //no linux o tar tem ordem de paramentros diferente de outros unix. Dar uma olhada
  $nomeArqTar = "/tmp/download".$sessionId.".tar.gz";
  $comando = "tar -cz  ";

  for ($i=3;$i<=$_SERVER[argc]-1;$i++) {
    //se o primeiro caracter do nome do arquivo for "/" entao eh um diretorio
    //e entao acrescentar o . na frente do nome para entender que eh no diretorio atual
    if ($_SERVER[argv][$i][0]=="/") {
      $_SERVER[argv][$i][0] = " ";
      $comando.= $_SERVER[argv][$i] . "/ ";   
    }
    else {
      $comando.= $_SERVER[argv][$i]." ";  
    }
  }
  $comando.= "> \"".$nomeArqTar."\"";


  echo "comando : $comando";
  exec($comando);
  
}


?>