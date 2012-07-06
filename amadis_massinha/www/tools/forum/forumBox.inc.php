<?php


class mensagemBox extends AEBox {
    var $LI, $linhasMsg, $acao, $imagem, $codForum, $codMensagem, $preTitulo, $preTexto, $resposta, $modo;

    function acao($a) {
        $this->acao = $a;
    }

    function add($lin) {
        $this->linhasMsg[] = $lin;
    }

    function addLI($lin) {
        $this->LI[] = $lin;
    }

    function addResposta($res) {
        $this->resposta[] = $res;
    }

    function addImagem($lin) {
        $this->imagem[] = $lin;
    }

    function codigos($for, $men, $modo, $showRel, $relacao) {
        $this->codForum = $for;
        $this->codMensagem = $men;
        $this->modo = $modo;
        $this->showRel = $showRel;
        $this->relacao = $relacao;
    }

    function setPreviewTitulo($tit) {
        $this->preTitulo = $tit;
    }
    
    function setPreviewTexto($tex) {
        $this->preTexto = $tex;
    }

    function imprime() {
        global $urlimagens;
        global $urlphp;
        global $numProjeto;
        global $_REQUEST;
        global $lang;

        parent::add("<table bgcolor=\"#E6E8EF\" width=\"100%\"><tr><td>&nbsp;&nbsp;</td><td>");

        if ($this->LI != "") {
            $LIs = "<ul>";
            foreach ($this->LI as $linha) {
                $LIs .= "<li class=\"comum\">";
                $LIs .= $linha;
                $LIs .= "</li>";
            }
            $LIs .= "</ul><hr>";
        }
        parent::add($LIs);

    //mensagem em si
        if ($this->linhasMsg != "") {
            parent::add("<div class=\"comum\" align=justify>");
            foreach ($this->linhasMsg as $linha) {
                parent::add ($linha);
            }
            parent::add ("</div>");
        }

    //imagens
        if ($this->imagem != "") {
            foreach ($this->imagem as $linha) {
                parent::add ("<p align=\"center\"><img src=\"simagem.php?in=$linha\" alt=\"Imagem\"></p>");
            }
        }
    //box confirmar
        if ($_REQUEST[voltar] == "cafe") {
            $voltar = "&voltar=cafe";
        }

        if ($this->acao == "preview") {
            parent::add ("<hr>");
            parent::add ("<TR>");
            parent::add ("<form name=enviar method=post action=\"manipula_men.php?projeto=".$numProjeto."#responder\" ENCTYPE=multipart/form-data>");
            parent::add ("<input type=\"hidden\" name=\"mensagem\" value=\"".$this->codMensagem."\">");
            parent::add ("<input type=\"hidden\" name=\"modo\" value=\"".$this->modo."\">");
            parent::add ("<input type=\"hidden\" name=\"showRel\" value=\"".$this->showRel."\">");
            parent::add ("<input type=\"hidden\" name=\"relacao\" value=\"".$this->relacao."\">");
            parent::add ("<input type=\"hidden\" name=\"forum\" value=\"".$this->codForum."\">");
            parent::add ("<input type=\"hidden\" name=\"frm_titulo\" value=\"".$this->preTitulo."\">");
            parent::add ("<input type=\"hidden\" name=\"frm_texto\"  value=\"".$this->preTexto."\">");
            parent::add ("<input type=\"hidden\" name=\"voltar\"  value=\"".$_REQUEST[voltar]."\">");
            parent::add ("<input type=hidden name=acao value=\"enviar\">");
            parent::add ("<TD class=\"whiteLine\" colspan=\"5\" width=\"100%\">");
            parent::add ("<div align=\"center\"><input type=\"submit\" value=\"$lang[enviar_mensagem]\" name=\"Enviar mensagem\">&nbsp;<input type=\"button\" value=\"Voltar � edi��o\" onclick=\"cancelaPreview()\"></div></TD>");
            parent::add ("<br>");
            parent::add ("</form>");
            parent::add ("</TR>");
        }


    //rela��o de respostas
        if ($this->acao != "preview") {
            if ($this->resposta != "") {
                parent::add ("<tr><td colspan=5>");
                parent::add ("<hr>");
                parent::add ("<font size=\"2\"><b>$lang[ver_respostas]</b></font><br>");
                foreach ($this->resposta as $linha) {
                    parent::add ("<font size=\"2\">".$linha."</font>");
                }
                parent::add ("<br></td</tr>");
            }
        }
        
        parent::add ("</table>");
        parent::imprime();
    }
    
}


?>