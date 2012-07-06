<?php

//define todos os box do amadis
class waBox extends RDPagObj {
    var $titulo, $corTitulo, $linhas, $cor, $borda, $widths, $colunas, $style, $tipo;
    var $asTable, $espaco, $paginado="0", $paginaAtual, $entradasPagina;
    var $bottomRow;


  //se o box for ter mais de uma coluna, o numero de colunas deve ser passado como parametro
    function waBox($num=1) {
        $this->asTable=1;
        $this->showTitle = 1;
        $this->colunas = $num;
    }

  //define o estilo do titulo entre unico ou multiplo ($tipo1 -> se existe um titulo unico ou se
  //temos um titulo para cada coluna) e o estilo do titulo ($tipo2 -> light ou normal).
    function setTipoTitulo($tipo1, $tipo2)  {
        $this->tipo = $tipo1;

        if (strtolower($tipo2) == "normal") {
            $this->corTitulo = "blueTitle";
            $this->espaco = array("&nbsp;&nbsp;");
        }
        if (strtolower($tipo2) == "light") {
            $this->corTitulo = "lightBlueTitle";
            $this->espaco = " ";
        }
    }
    
  //ativa a flag para que todos os dados sejam adicionados em uma linha unica
    function setOneRowTable() {
        $this->asTable = 0;
    }

  //adiciona o titulo (ou titulos) das colunas. se forem colunas, devem ser passados em um array
    function setTitulo($titulos) {
        //passa o titulo pra maiusculo sem perder os acentos

        $tr["ACUTE;"] = "acute;";
        $tr["CIRC;"]  = "circ;";
        $tr["UML;"] =  "uml;";
        $tr["GRAVE;"] = "grave;";
        $tr["TILDE;"] = "tilde;";
        $tr["CEDIL;"]  = "cedil;";
        $tr["NBSP;"] = "nbsp;";


        if(is_array($titulos)) {
            $this->titulo = $titulos;
        }
        else {
            $this->titulo = array($titulos);
        }
        foreach($this->titulo as $k=>$titulo) {
            $this->titulo[$k] = strtr(strtoupper($titulo),$tr);
        }
    }

  //define o tamanho (em %) de cada coluna. usa-se setWidth(coluna, tamanho)
    function setWidth($col, $width) {
        $this->widths[$col] = $width;
    }

  //seleciona a flag para definir paginas na mostragem dos itens. deve ser passado como
  //parametro o numero de itens por pagina a mostrar.
    function setPaginas($entrada) {
        $this->paginado = "1";
        $this->entradasPagina = $entrada;
    }

  //definido quando a tabela utilizar a pagina toda.
    function setFullPage() {
        $this->fullpage = 1;
    }

  //define um style padrao dos html para cada coluna que se desejar
    function setStyle($col, $style) {
        $this->style[$col] = $style;
    }

  //define uma linha que devera ser mostrada em cada pagina
  //da tabela
    function setBottomRow($conteudo) {
        $this->bottomRow = $conteudo;
    }
    


  //adiciona linhas no waBox. se o wabox tiver mais de uma coluna, deve ser adicionado em um array.
  //se for um wabox com subtitulos, deve enviar um array, onde o primeiro item eh somente um outro
  //array. dentro desse ultimo, o primeiro item deve ser o texto do subtitulo, e o segundo item
  //deve ser um array com as linhas que este subtitulo contem.
    function add($linhas) {
        if(is_array($linhas)) {
            $this->linhas[] = $linhas;
        }
        else {
            $this->linhas[] = array($linhas);
        };
    }

  //seleciona a cor das linhas do wabox entre verde ou branco
    function setColor($color) {
        if (strtolower($color) == "branco") {
            $this->cor = "thinWhiteLine";
        }
        if (strtolower($color) == "verde") {
            $this->cor = "blueLine";
            $this->borda = "1";
        }
    }

  //imprime o wabox
    function imprime() {
        global $urlimagens,$lang, $_REQUEST;

    //verifica as opcoes que sao passadas no endereco pra recolocar elas no link
        if ($this->paginado) {
            foreach ($_REQUEST as $nome=>$opcao) {
                if ($nome != "pagina") {
                    $opcoes .= "&$nome=$opcao";
                }
            }
             
    //verifica em quantas paginas serah dividido
            $paginas = intval((count($this->linhas) / $this->entradasPagina)) + 1;
            if (fmod(count($this->linhas) , $this->entradasPagina) == "0") {
                $paginas = $paginas -1;
            }
        }

    //verifica a pagina atual
        if ($_REQUEST[pagina] != "") {
            $this->paginaAtual = $_REQUEST[pagina];
        }
        else {
            $this->paginaAtual = 1;
        }

    //define o tamanho da tabela em 100% quando nao abre uma janela nova
        if(!empty($this->fullpage)) $h = " height=\"100%\" ";

    //inicia uma nova tabela com a borda... quando eh uma tabela verde.
        if ($this->borda == "1") {
            parent::add ("<TABLE class=\"blueTable\" border=\"1\" width=\"100%\" $h>");
        }
        
        else {
            parent::add ("<TABLE class=\"border\" border=\"1\" cellspacing=\"0\" cellpading=\"0\" width=\"100%\" $h><TR><TD>");
            parent::add ("<TABLE width=\"100%\" style=\"table-layout: auto\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" $h>");
        }


    //imprime o titulo da tabela

        if($this->showTitle) {
            parent::add ("<TR>");

            if(($this->colunas>1) && (count($this->titulo)==1)) {
                $i=0;


                parent::add ("<TD colspan=$this->colunas class=\"".$this->corTitulo."\" width=\"".$this->widths[$i]."\" height=\"25\" VALIGN=\"middle\" style=\"".$this->style[$i]."\"><font size=\"2\">".$this->espaco[$i].$this->titulo[$i]."</font></TD>");
            }
            else {
                for($i=0;$i<$this->colunas;$i++) {

                    parent::add ("<TD class=\"".$this->corTitulo."\" width=\"".$this->widths[$i]."\" height=\"25\" VALIGN=\"middle\" style=\"".$this->style[$i]."\"><font size=\"2\">".$this->espaco[$i].$this->titulo[$i]."</font></TD>");
                }
            }
            parent::add ("</TR>");

            if ($this->borda != "1") {
                parent::add ("<TR>");
                parent::add ("<TD colspan=\"$this->colunas\" background=\"".$urlimagens."pattern9.gif\" width=\"100%\"><img src=\"".$urlimagens."pattern9.gif\" width=\"1\" height=\"2\"></TD>");
                parent::add ("</TR>");
            }
        }

    //coloca o indice de paginas, caso a opcao paginado seja selecionada.
        if ($this->paginado and $paginas > 1) {
            parent::add ("<tr><td class=$this->cor colspan=$this->colunas align=right><font size=2>$lang[pagina]&nbsp;<b>".$this->paginaAtual."</b>&nbsp;$lang[de]&nbsp;<b>".$paginas."</font></b></td></tr>");
            if ($this->cor == "thinWhiteLine") {
                parent::add ("<tr><td class=$this->cor colspan=$this->colunas align=right><hr></td></tr>");
            }
        }

    //adiciona os dados normalmente quando a opcao setOneRowTable nao foi ativada.
        if(!$this->asTable) {

            parent::add("<!- aqui >");
            parent::add ("<TR><TD class=\"".$this->cor."\" width=\"".$this->widths[$i]."\" style=\"".$this->style[$i]."\" valign=\"top\"><font size=\"2\">");
        };

        if ($this->linhas != "") {
            foreach ($this->linhas as $num=>$linha) {
    //adiciona os dados quando temos a definicao de subtitulos (aqui a paginacao ainda nao funciona)
                if (is_array($linha[0]) && ($this->asTable)) {
                    foreach ($linha as $entrada) {
                        parent::add ("<TR>");
                        parent::add ("<TD class=\"lightblueTitle\" colspan=\"".$this->colunas."\"><font size=\"2\">");
                        parent::add ($entrada[0]);
                        parent::add ("</font></TD>");
                        parent::add ("</TR>");
                        foreach ($entrada[1] as $text) {
                            parent::add ("<TR>");
                            for($i=0;$i<$this->colunas;$i++) {
                                parent::add ("<TD class=\"".$this->cor."\" width=\"".$this->widths[$i]."\" style=\"".$this->style[$i]."\"><font size=\"2\">".$text[$i]."</font></TD>");
                            }
                            parent::add ("</TR>");
                        }
                    }
                }

    //adiciona os dados quando nao temos subtitulos
                else {
                    if($this->asTable) {
                         
        ///adiciona os dados que pertencem a pagina atual
                        if ($this->paginado) {
                            $menor = ($this->entradasPagina * ($this->paginaAtual - 1));
                            $maior = ($this->entradasPagina * $this->paginaAtual) -1;
                            if ($num >= $menor and $num <= $maior ) {
                                parent::add ("<TR>");
                                if (count($linha) == $this->colunas) {
                                    for($i=0;$i<$this->colunas;$i++) {
                                        parent::add ("<TD class=\"".$this->cor."\" width=\"".$this->widths[$i]."\" style=\"".$this->style[$i]."\"><font size=\"2\">".$linha[$i]."</font></TD>");
                                    }
                                }
                                else {
                                    $i = 0;
                                    parent::add ("<TD class=\"".$this->cor."\" colspan=$this->colunas width=\"".$this->widths[$i]."\" style=\"".$this->style[$i]."\"><font size=\"2\">".$linha[$i]."</font></TD>");
                                }
                            }
                            parent::add ("</TR>");
                        }
                         
        //adiciona os dados normalmente quando a opcao paginado nao foi selecionada
                        else {
                            parent::add ("<TR>");
                            for($i=0;$i<$this->colunas;$i++) {
                                parent::add ("<TD class=\"".$this->cor."\" width=\"".$this->widths[$i]."\" style=\"".$this->style[$i]."\"><font size=\"2\">".$linha[$i]."</font></TD>");
                            }
                            parent::add ("</TR>");
                        }
                    }

      //adiciona os dados quando a opcao setOneRowTable estah selecionada.
                    else {
                        parent::add($linha[0]);
                    }
                }
            }

      //bottom row eh uma linha que devera sempre ser impressa no final de cada pagina da tabela
            if (!empty($this->bottomRow)) {
                parent::add("<tr><td colspan=\"".$this->colunas."\" class=\"".$this->cor."\">");
                parent::add($this->bottomRow);
                parent::add("</td></tr>");
            }

      //adiciona os links das paginas, quando esta opcao estiver selecionada
            if ($this->paginado and $paginas > 1) {
                for ($d = 1; $d <= $paginas; $d++) {
                    if ($d == $this->paginaAtual) {
                        $links .= "$d&nbsp;";
                    }
                    else {
                        $links .= "<a href=\"".$_SERVER[PHP_SELF]."?pagina=$d&".$opcoes."\">$d</a>&nbsp;";
                    }
                }

                if ($this->cor == "thinWhiteLine") {
                    parent::add ("<tr><td class=$this->cor colspan=$this->colunas align=right><hr></td></tr>");
                }

                parent::add ("<tr><td colspan=$this->colunas class=$this->cor align=right><font size=2>".$lang[ir_pagina].":&nbsp;".$links."</font></tr></td>");

            }
        }

    //adiciona o "nenhum item selecionado" quando a lista de itens a adicionar estiver vazia.
        else {
            parent::add ("<TD class=\"".$this->cor."\" width=\"".$this->widths[$i]."\" colspan=\"".$this->colunas."\" style=\"".$this->style[$i]."\" valign=\"top\"><font size=\"2\"><i><b>&nbsp;&nbsp;$lang[nenhum_item]</b></i></font></TD>");
        }


    //adicionam-se os fins de campos, linhas e tabelas...
        if(!$this->asTable) {
            parent::add("</font></td></tr>");
        }

        parent::add ("</table>");

        if ($this->borda != "1") {
            parent::add ("</td></tr></table>");
        }

        parent::imprime();
    }
}
