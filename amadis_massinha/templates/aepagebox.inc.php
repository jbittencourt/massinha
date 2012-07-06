<?php


class AEPageBox extends AEBox 
{
    
    var $cabecalho = array(), $itensadd = array(), $entradas, $bloco, $numItens=0, $linkClass="comum";

    function AEPageBox($ent){
        $this->entradas = $ent;
        $this->bloco = 0;
        $this->itensadd[0] == array();

        $this->blockColor[0] ="white";
        $this->blockColor[1] ="#E6E8EF";
    }

    function addItem($item,$link="", $insbloco="") {
        $item = "$item";
        if(!empty($link)) {
            $item = "<a href=\"$link\">$item</a>";
        }
        if (!$insbloco) $insbloco = $this->bloco;
        $this->itensadd[$insbloco][] = $item;
        $this->numItens++;
    }
    
    function add($linha){
        $this->cabecalho[] = $linha;
    }

    function newBlock() {
        $this->bloco++;
        $this->itensadd[$this->bloco] = array();
    }

    function setLinkClass($classe) {
        $this->linkClass = $classe;
    }

    function imprime(){
        global $lang, $_REQUEST;

        parent::add ("<table width=\"100%\" border=0>");

    //anota na var $opcoes todas as variaveis que devem ser repassadas
        $opcoes = "";
        foreach ($_REQUEST as $nome=>$opcao) {
            if (($nome != "ae_page_to_show")) {
                $opcoes .= "&$nome=$opcao";
            }
        }

    //verifica em quantas paginas serah dividido
        $paginas = intval($this->numItens / $this->entradas) + 1;
        $resto = fmod($this->numItens, $this->entradas);
        if ($resto == "0") $paginas = $paginas -1;

    //verifica a pagina atual
        if ($_REQUEST[ae_page_to_show] != "") {
            $paginaAtual = $_REQUEST[ae_page_to_show];
        }
        else {
            $paginaAtual = 1;
        }

    //se existe mais de uma p�gina, mostra o �ndice em cima
        if ($paginas > 1) {
            parent::add ("<tr><td class=\"comum\" align=right>$lang[pagina]&nbsp;<b>".$paginaAtual."</b>&nbsp;$lang[de]&nbsp;<b>".$paginas."</b></td></tr>");
            parent::add ("<tr><td bgcolor=\"#d2d2d2\"><img src=\"$urlimagens/dot.gif\" width=1></td></tr>");
        }

    //se o cara deu um add comum... ele adiciona sempre esse conte�do como se fosse um cabe�alho
        if (!empty($this->cabecalho)) {
            parent::add ("<tr><td class=\"comum\">");
            foreach ($this->cabecalho as $linha) {
                parent::add ($linha);
            }
            parent::add("</td></tr>");
        }

        if (!empty($this->itensadd)) {
      //define os limites de impress�o
            $menor = ($this->entradas * ($paginaAtual - 1));
            $maior = ($this->entradas * $paginaAtual);

            $itemAtual = 0;

      //pega cada bloco e chama de item
            foreach ($this->itensadd as $num=>$item) {

	//define a cor de fundo de acordo com ser par ou impar
                if (fmod($num,2) == "0") $bgcolor = $this->blockColor[0];
                else $bgcolor = $this->blockColor[1];

                if(!empty($this->blockClass)) {
                    if (fmod($num,2) == "0") $class = $this->blockClass[0];
                    else $class = $this->blockClass[1];
                }
                else {
                    $class = "comum";
                }


	//adiciona uma tabela por bloco
                parent::add ("<tr><td class=\"$classx\" bgcolor=\"$bgcolor\"><table width=\"100%\">");

	//pega cada item do bloco e chama de it
                foreach ($item as $it){
                    if (($itemAtual >= $menor) and ($itemAtual < $maior)) {
                        parent::add ("<tr width=\"100%\" align=justify><td width=\"100%\" class=\"comum\">$it</td></tr>");
                    }
                    $itemAtual++;
                }

                parent::add ("</table></td></tr>");
            }

      //adiciona os links das paginas, quando existirem
            if ($paginas > 1) {
                parent::add ("<tr><td bgcolor=\"#d2d2d2\"><img src=\"$urlimagens/dot.gif\" width=1></td></tr>");
                for ($d = 1; $d <= $paginas; $d++) {
                    if ($d == $paginaAtual) $links .= "$d&nbsp;";
                    else $links .= "<a class=\"".$this->linkClass."\" href=\"".$_SERVER[PHP_SELF]."?ae_page_to_show=$d".$opcoes."\">$d</a>&nbsp;";
                }
                parent::add ("<tr><td class=\"comum\" align=right>".$lang[ir_pagina].":&nbsp;".$links."</font></tr></td>");
            }
        }

        parent::imprime();

    }
}

