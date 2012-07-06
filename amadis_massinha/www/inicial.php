<?

include("config.inc.php");


$ui = new RDui("inicial", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);
$noticias = $_SESSION[usuario]->listaNoticias();

$pag = new AEInicial();
$pag->add ("<br>");
$pag->add ("<table eidth=\"100%\"><tr><td width=\"49%\">");

//lista os projetos do usuario
$box = new AEBox();
$box->setTitle("img_tit_oque_amadis.gif");
$box->add ("<p align=justify class=\"comum\">$lang[desc_amadis]</p>");

$pag->add($box);

$pag->add ("</td><td width=\"2%\">&nbsp;</td><td width=\"49%\" valign=\"top\">");

$boxnot = new AEBox();
$boxnot->setTitle("img_tit_novidades_inicial.gif");

if ($noticias->records) {
  foreach ($noticias->records as $noticia) {

    if ($noticia->flaLida == "0") {
      $boxnot->addItem ($noticia->desNoticia);

      $notLida = new AMNoticia($noticia->codNoticia);
      $notLida->flaLida = time();
      $notLida->salva();
    }

    if ($noticia->flaLida != "0" and $noticia->flaLida < (time() + 86400) ) {
      $boxnot->addItem ($noticia->desNoticia);
    }

  }
}


if(empty($_SESSION[email_recent])) {
#  $email = new RDImapMail();
#  $_SESSION[email_recent] = $email->getNumRecentes();
}

if($_SESSION[email_recent]>0) {
  $link = "$urlferramentas/email/email.php";
  if($_SESSION[email_recent]==1) {
    $boxnot->addItem($lang[mensagens_emails0a],$link);
  }
  else {
      $boxnot->addItem($lang[mensagens_emails0b].$_SESSION[email_recent].$lang[mensagens_emails1b],$link);
  }

}
else {
  $boxnot->addItem($lang[nenhum_email]);
}

$boxnot->add ("&nbsp;");

$pag->add ($boxnot);
$pag->add ("</td></tr></table>");

$pag->imprime();

?>
