<?

include_once("$pathtemplates/aemain.inc.php");



class AEChat extends AEMain {
  

  function AEChat() {
    global $urlimagens, $urlimlang;
    $this->aemain();


    $this->setMenuSuperior("$urlimagens/bg_chat.gif",
			   "$urlimagens/img_chat_01.jpg",
			   "$urlimagens/img_chat_sombra.gif");

    $this->setImgId("$urlimlang/img_top_chat.gif");

    $this->openNavMenu();
  }
}



?>