<?php

class AEMainMenu extends RDPagObj
{
    var $itens;

    function addItem($link,$im) {

        if(is_array($im)) {
            $temp = new WSwapImage($link,$im[0],$im[1]);
        }
        else {
            $temp ="<a href=\"$item[link]\" border=0><img src=\"$item[imagem]\"></a>";
        }


        $this->itens[] = $temp;
    }

    function imprime() {


        if(!empty($this->itens)) {

            parent::add("<table border=0 cellspacing=0 cellpadding=0><tr>");
            foreach($this->itens as $item) {
                parent::add("<td>");
                parent::add($item);
                parent::add("</td>");
            }
            parent::add("</tr></table>");
        }

        parent::imprime();

    }

}



?>