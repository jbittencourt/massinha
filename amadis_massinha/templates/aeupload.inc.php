<?php

class AEUpload extends AEMain
{

    function AEUpload($secao="visualiza") {
        global $urlimagens, $urlimlang;
        $this->aemain(0); //desabilita o menu de navegacao
        
        $this->setMenuSuperior("$urlimagens/bg_forum.gif",
			   "$urlimagens/img_forum_01.jpg",
			   "$urlimagens/img_forum_sombra.gif");

        $this->setImgId("$urlimlang/img_tit_publicar_minha_pagina.gif");

        $this->openNavMenu();


        $this->add("<br>");
        $this->add("<table width=\"100%\">");
        switch($secao) {
            case "visualiza":
                $this->add("<tr><td><img src=\"$urlimlang/img_tit_arquivos_publicados.gif\"></td></tr>");
                break;
            case "criaDir":
                $this->add("<tr><td><img src=\"$urlimlang/img_tit_criar_nova_pasta.gif\"></td></tr>");
                break;
            case "envia":
                $this->add("<tr><td><img src=\"$urlimlang/img_tit_enviar_arquivos.gif\"></td></tr>");
                break;

        }
        $this->add("</table>");

    }

    
    
}

