<?php

class AMPagina extends RDPagina {

    function AMPagina() {
        global $urlimagens,$url;

        $this->setMargin(0,0,0,0);
        $this->setBgColor("#ffffff");
        $this->addStyleFile("$url/paginas/normal.css.php");
    }

}




/*

class templateFerramentasAmadis extends templateAmadisNav {

    function templateFerramentasAmadis() {
        global $cafe, $url, $lang;

        $this->templateAmadisNav();
        $this->setMenuPrinc($lang[ferramentas]);
    
    }
}


class templateProjetoAmadis extends templateAmadisNav {

    function templateProjetoAmadis() {
        global $lang;

        $this->templateAmadisNav();
        $this->setMenuPrinc($lang[projetos]);

    }
}


class templateComprimissos extends  RDPagina {

    function templateComprimissos() {
        global $config_ini;
        $this->setMargin(0,0,0,0);
        $this->addStyleFile($config_ini[Internet][urlcss]."/normal.css.php");
        $this->setBgColor("#ffffff");

    }


}


class templateWebfolioAmadis extends templateAmadisNav {

    function templateWebfolioAmadis() {
        global $url, $lang;
        $this->templateAmadisNav();
        $this->setMenuPrinc($lang[webfolio]);

        $jswin = new RDJSWindow("$url/ferramentas/agenda/compromisso.php","Agenda",600,400);
    
        $menul = new menuLateral(array($lang[inicial] => "$url/ferramentas/webfolio/webfolio.php",
        $lang[agenda] =>  $jswin->getScript(),
        $lang[correio] => "$url/ferramentas/email/email.php",
        $lang[diario_pessoal] => "$url/ferramentas/diario/diariopessoal.php",
        $lang[socorro] => "$url/ajuda/socorro.php"  ));


        $menul->setJSMenuLateral(1);
        $this->setMenuLateral($menul);
        $this->ativamenulateral();

    }
}


class templateOficinaAmadis extends templateAmadisNav {

    function templateOficinaAmadis() {
        global $lang;
        $this->templateAmadisNav();
        $this->setMenuPrinc($lang[oficinas]);
    }
}

class templateSeminarioAmadis extends templateAmadisNav {

    function templateSeminarioAmadis() {
        global $lang;
        $this->templateAmadisNav();
        $this->setMenuPrinc($lang[seminarios]);
    }
}


class templateEditaProjetoAmadis extends templateWebfolioAmadis {

    function templateEditaProjetoAmadis() {
        global $lang, $proj, $url;

        $this->templateWebFolioAmadis();

        $this->add(new AMMenuAuxiliar(array($lang[upload]=> $url."/projeto/upload.php?codProjeto=".$proj->codProjeto,
        $lang[forum] => $url."/ferramentas/forum/forum.php?projeto=".$proj->codProjeto,
        $lang[chat] => $url."/ferramentas/chat/chat.php?projeto=".$proj->codProjeto,
        $lang[diario_projeto] => $url."/ferramentas/diario/diarioprojeto.php?projeto=".$proj->codProjeto)));
    }

}

class templateAdminAmadis extends templateWebfolioAmadis {

    function templateAdminAmadis() {
        global $lang, $proj, $url;

        $this->templateAmadisNav();
        $this->setMenuPrinc($lang[administracao]);


    }

}

*/

?>