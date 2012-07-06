<?php
class templateAmadisNav extends templateAmadis {

    function templateAmadisNav() {
        global $config_ini, $lang;

        $url = $config_ini[Internet][url];
        $urljs = $config_ini[Internet][urljs];

        $tr["ACUTE;"] = "acute;";
        $tr["CIRC;"] = "circ;";
        $tr["UML;"] = "uml;";
        $tr["GRAVE;"] = "grave;";
        $tr["TILDE;"] = "tilde;";
        $tr["CEDIL;"] = "cedil;";
        $tr["NBSP;"] = "nbsp;";

        $menu[] = $lang[projetos];
        $menu[] = $lang[seminarios];
        $menu[] = $lang[oficinas];
        $menu[] = $lang[webfolio];
        $menu[] = $lang[ferramentas];
        $menu[] = $lang[ajuda];
        $menu[] = $lang[administracao];

        foreach ($menu as $k=>$menuItem) {
            $menu[$k] = strtr(strtoupper($menuItem), $tr);
        }

        if ($_SESSION[usuario]->flaSuper == "1") {
            $this->itensMenuPrinc = array (array($menu[0], $url),
            array($menu[1], $url."/ferramentas/oficinas/oficinas.php?flaSem=1"),
            array($menu[2], $url."/ferramentas/oficinas/oficinas.php"),
            array($menu[3], $url."/ferramentas/webfolio/webfolio.php"),
            array($menu[4], $url."/ferramentas/webfolio/ferramentas.php"),
            array($menu[5], $url."/ferramentas/ajuda/help.php"),
            array($menu[6], $url."/ferramentas/admin/admin.php"));
        }
        else {
            $this->itensMenuPrinc = array (array($menu[0], $url),
            array($menu[1], $url."/ferramentas/oficinas/oficinas.php?flaSem=1"),
            array($menu[2], $url."/ferramentas/oficinas/oficinas.php"),
            array($menu[3], $url."/ferramentas/webfolio/webfolio.php"),
            array($menu[4], $url."/ferramentas/webfolio/ferramentas.php"),
            array($menu[5], $url."/ferramentas/ajuda/help.php")
            );
        }
        if(isset($_SESSION[usuario])) {
            $this->addJSFile("$urljs/finder.js");
            $this->setOnLoad("abrePopupFinder('$url/ferramentas/finder/finder.php')");
        };
    }

}
