<?php

class AEDiario extends AEMain 
{
    
    function AEDiario() {
        global $urlimagens, $urlimlang;
        $this->aemain();


        $this->setMenuSuperior("$urlimagens/bg_diario.gif",
			   "$urlimagens/img_diario_01.jpg",
			   "$urlimagens/img_diario_sombra.gif");

        $this->setImgId("$urlimlang/img_top_diario.gif");

        $this->openNavMenu();
    }
}

