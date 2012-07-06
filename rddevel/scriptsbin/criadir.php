<?

$config_ini[Arquivos][default_uid] = 504;
$config_ini[Arquivos][default_gid] = 500;
$config_ini[Arquivos][default_perm] = 0755; 
  

if($_SERVER[argc]<2) { printf("Numero insuficiente de parametros"); return 0; };
  $dir = $_SERVER[argv][1];

  if(file_exists($dir)) { printf("Diretorio já existe"); return 0; };
	
$ret=mkdir($dir,$config_ini[Arquivos][default_perm]);

  $auid = $config_ini[Arquivos][default_uid];
  $agid = $config_ini[Arquivos][default_gid];    
 
  settype($auid,"integer");
  settype($agid,"integer");
  
  if(chown($dir,$auid)==false) { printf("Nao consegui alterar o dono de".$dir); return 0; };
  if(chgrp($dir,$agid)==false) { printf("Nao consegui alterar o grupo de".$dir); return 0; };  
  
if(!empty($config_ini[Arquivos][default_perm])) {
  chmod($dir,$config_ini[Arquivos][default_perm]);
}
else {
  chmod($dir,"a+rx");
}



?>
