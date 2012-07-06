<?php
$sem_login = 1;
include_once("../../config.inc.php");

$temp = unserialize($_SESSION[tmpimagens]);
$imagem = $temp[$_REQUEST[in]];

if (!empty($imagem->desDados)) {
  header("Content-Type: ".$imagem->desTipoMime);
  echo stripSlashes($imagem->desDados);        
  //os slashs tem que serem retirados, pois esse
  // objeto � organizado para salvar esse dado no db
  //entretanto nesse caso ele ainda n�o foi salva e o
  //os slashes ainda permanecem.
}
?>
