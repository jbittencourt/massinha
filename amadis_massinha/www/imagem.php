<?php
$sem_login = 1;
include_once("config.inc.php");

$imagem = new RDImagem($_REQUEST[imagem]);

if (!empty($imagem->desDados)) {
  header("Content-Type: ".$imagem->desTipoMime);
  echo $imagem->desDados;
}

?>
