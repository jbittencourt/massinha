<?php

class AEPaginas extends AEMain
{

    function AEPaginas($mostratitulo="1") {
        global $urlimagens, $urlimlang;
        $this->aemain();

        $this->setMenuSuperior("$urlimagens/bg_paginas.gif",
			   "$urlimagens/img_paginas_01.jpg",
			   "$urlimagens/img_paginas_sombra.gif");

        $this->slidein->setMode(SLIDEINMENU_MODE_OPEN);
        $this->navmenu->locked = 1;

        $this->setImgId("$urlimlang/img_top_paginas.gif");

    }

}

