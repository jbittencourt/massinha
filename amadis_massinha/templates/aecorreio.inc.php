<?php

class AECorreio extends AEMain
{
    

    function AECorreio() {
        global $urlimagens, $urlimlang;
        $this->aemain();


        $this->setMenuSuperior("$urlimagens/bg_correio.gif",
			   "$urlimagens/img_correio_01.jpg",
			   "$urlimagens/img_correio_sombra.gif");

        $this->setImgId("$urlimlang/img_top_correio.gif");

        $this->openNavMenu();
    }
}
