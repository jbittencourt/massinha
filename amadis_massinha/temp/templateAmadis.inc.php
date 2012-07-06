<?php
class templateAmadis extends AMPagina {
    var $itensMenuPrinc,$selectMenuPrinc;
    var $itensMenuSec,$selectMenuSec;
    var $Tbody,$ativaMenuLateral,$itensMenuLateral;


    function setMenuPrinc($item) {
        $this->selectMenuPrinc = $item;
    }

    function add($item) {
        $this->Tbody[] = $item;
    }
    
    function setMenuLateral($itens) {
        $this->itensMenuLateral = $itens;
    }
    
    function ativaMenuLateral() {
        $this->ativaMenuLateral=1;
    }

    function setJSMenuLateral($item) {
        $this->jsmenulateral[$item] =1;
    }
    
    function imprime() {
        global $urltema,$url;


        $this->AMPagina();
        $this->setTitle("Amadis");

        $menu = new mainMenu();
        $menu->menuPrinc($this->itensMenuPrinc, $this->selectMenuPrinc);
        $menu->menuLateral($this->ativaMenuLateral);
        parent::add($menu);

        if($this->ativaMenuLateral) {
            parent::add($this->itensMenuLateral);
        };

//    parent::add("<img src=\"$urltema/space.gif\" height=\"20\" width=\"10\">");
        
        if(!empty($this->Tbody)) {
            foreach($this->Tbody as $k=>$item) {
                parent::add($item);
            }
        }

        parent::add("<td width=\"3%\" valign=\"top\" align=\"center\" bgcolor=\"ffffff\"></td></table>");
        parent::add("<br>");
   
        parent::imprime();
    }

}
