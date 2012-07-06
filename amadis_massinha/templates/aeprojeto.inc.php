<?php


class AEProjeto extends AEMain
{
    

    function AEProjeto() {
        global $urlimagens, $urlimlang;
        $this->aemain();


        $this->setMenuSuperior("$urlimagens/bg_ilustra.gif",
			   "$urlimagens/img_projetos_01.jpg",
			   "$urlimagens/img_ilustra_sombra.gif");


        $this->setImgId("$urlimlang/img_top_projetos.gif");

        $this->openNavMenu();


    }
}

