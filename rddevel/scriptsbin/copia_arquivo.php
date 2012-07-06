<?

$sem_login = 1;
//include_once("/usr/local/amadis/amadis/config.inc.php");
//include_once($pathlib."erro.inc.php");

$config_ini[arquivos][default_user_uid] = 0;
$config_ini[arquivos][default_grp_gid] = 1005;
$config_ini[arquivos][default_perm] = 0755; 

   
$path = trim($config_file[Diretorios][pathUpload]);

//confere o numero de parametros  
if($_SERVER[argc]<3) { echo "Numero insuficiente de parametros: $_SERVER[argc]";  return 0;};

$acao = $_SERVER[argv][1];
$src = escapeshellarg($_SERVER[argv][2]);
$dst = escapeshellarg($_SERVER[argv][3]);
$rec = $_SERVER[argv][4];

if(!( ($acao=="mv") || ($acao=="cp"))) { echo "Não consegui identificar a acao a ser realizada"; return 0; };
    

if ($rec) {
  //copia recursiva
  
  $cmd = "cp -R $src $dst";
  
  echo "comando : $cmd";
  
  $linhas = array();
  $ret = exec($cmd,$linhas);
  
  //  $change_owner = "chown -R ".$config_ini[Arquivos][default_user_uid].":".$config_ini[Arquivos][default_grp_gid]." ".$dst;
  //$change_perm_user = "chmod -R 0775 $dst";
  
  //exec($change_owner);
  //exec($change_perm_user);
  if($acao=="mv") { unlink($src); };

}
else {

  /*Isto que esta comentado foi feito por maicon. Talvez seja melhor usar este codigo. 
   
  if(!copy($src,$dst)) {
    
    //logError($PHP_SELF,"Erro desconhecido ao copiar o arquivo"); return 3; 
  }
  else {
    if(chown($dst,(int)$config_ini[Arquivos][default__uid])==false) { logError($PHP_SELF,"Nao consegui alterar o dono de".$dst); return 0; };
    if(chgrp($dst,(int)$config_ini[Arquivos][default_gid])==false) { logError($PHP_SELF,"Nao consegui alterar o grupo de".$dst); return 0; };  

    //da direitos para o arquivo ser exluido
    chmod($src,"a+rx");  

    //da  os direitos default do arquivo
    chmod($dst,0775);
      
    if($acao=="mv") { unlink($src); };
  }; 
  echo $copy;*/
  
  
  $cmd = "cp $src $dst 2>&1; chmod a+rx $dst";
  echo "CMD: $cmd\n";
  passthru($cmd,$linhas);
  if($acao=="mv") { unlink($src); };
   
}

?>
