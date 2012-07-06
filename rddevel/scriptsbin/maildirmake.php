<?

$config_ini[Arquivos][default_uid] ="500";
$config_ini[Arquivos][default_gid] ="500"; 

print_r($_SERVER);
if($_SERVER[argc]<2) { printf("Numero insuficiente de parametros"); return 0; };

$dir = $_SERVER[argv][1];

if(file_exists($dir)) { printf("Diretorio já existe"); return 0; };

$ret=exec("/usr/bin/maildirmake $dir",$linhas);
print_r($linhas);
$auid = $config_ini[Arquivos][default_uid];
$agid = $config_ini[Arquivos][default_gid];    
  
settype($auid,"integer");
settype($agid,"integer");
  
$ret=exec("chown -R $auid:$agid $dir",$linhas);
print_r($linhas);

$ret=exec("chmod -R u=rwx $dir",$linhas);
print_r($linhas);



?>
