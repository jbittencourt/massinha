<?php

class AEInicial extends AEMain 
{
    

    function AEInicial() {
        global $urlimagens, $urlimlang;
        $this->aemain();


        $this->setMenuSuperior("$urlimagens/bg_inicial.gif",
			   "$urlimagens/img_inicial_01.jpg",
			   "$urlimagens/img_inicial_sombra.gif");


        $this->setImgId("$urlimlang/img_top_inicial.gif");

        $this->openNavMenu();
    }
}
