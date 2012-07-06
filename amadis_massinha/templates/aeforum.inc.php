<?php

class AEForum extends AEMain
{
    

    function AEForum() {
        global $urlimagens, $urlimlang;
        $this->aemain();


        $this->setMenuSuperior("$urlimagens/bg_forum.gif",
			   "$urlimagens/img_forum_01.jpg",
			   "$urlimagens/img_forum_sombra.gif");

        $this->setImgId("$urlimlang/img_top_foruns.gif");

        $this->openNavMenu();
    }
}

