<?php

include_once("../../config.inc.php");

$ui = new RDui("forum", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AEForum();
$pag->add ("<br>");

if(empty($_REQUEST[forum])) {
    echo "Acesso n�o permitido";
    die();
}


if(!empty($_REQUEST[acao])) {
    switch($_REQUEST[acao]) {
        case "A_mensagem_enviada":
            $temp_mens[] = $lang[mensagem_enviada];
            break;
    }
}



$forum = new AMForumAmadis($_REQUEST[forum]);
$mens = $forum->listaMensagens();

if ($_REQUEST[projeto]) {
    $voltar = "projeto=".$_REQUEST[projeto];
}

//ve se ele vai mostrar toda a mensagem ou so o titulo
if ($_REQUEST[modo] == "titulo") {
    $entradaspagina = 30;
    $entradasopcao = array($lang[ver_toda_mensagem], "mensagens.php?forum=$_REQUEST[forum]&modo=toda");
    $modo = "titulo";
}
else {
    $entradaspagina = 10;
    $entradasopcao = array($lang[ver_titulos_mensagens], "mensagens.php?forum=$_REQUEST[forum]&modo=titulo");
    $modo = "toda";
}


//cria o menuauxiliar
$itens[$lang[voltar_lista_foruns]] = "forum.php?".$voltar;
//if($_SESSION[forum_perm][$forum->codForum][can_post]) $itens[$lang[compor]] = "manipula_men.php?acao=compor&forum=$_REQUEST[forum]";
$itens[$lang[compor]] = "manipula_men.php?acao=compor&forum=$_REQUEST[forum]";
$itens[$entradasopcao[0]] = $entradasopcao[1];

$pag->setSubMenu($itens,"comum");
$pag->setMens($temp_mens);



$pag->add("<p class=\"comum\"><font size=3><b>".$forum->nomForum."</font></b></p>");

$pag->add("<TABLE width=\"100%\">");
$pag->add("<TR>");
$pag->add("<TD class=\"comum\"><font size=\"2\">");
$pag->add("<ul>");
$pag->add("<li><b>".$lang[numero_mensagens].":&nbsp;</b>".count($mens)."</li>");
$pag->add("<li><b>".$lang[data_abertura].":&nbsp;</b>".date("j/n/Y G:i",$forum->tempo)."</li>");
$pag->add("</ul></font></TD>");
$pag->add("</TR>");
$pag->add("</TABLE>");

$tabela = new AEPageBox($entradaspagina);
$tabela->setTitle("img_tit_forum_mensagens.gif");

$tabela->add ("<table width=\"100%\">");
//ve a lista de mensagens que nao sao resposta de nenhuma outra

$primeirobloco = 1;
if ($mens != "") {
    foreach ($mens as $cod=>$men)  {
        if($men['mensagem']->codMensagemPai==0) {
            if ($primeirobloco != "1") {
                $tabela->newBlock();
            }
            
            $primeirobloco = 0;
            $lista = $forum->organizaMensagens($cod,$mens,"0");

      //ve a relacao da filha com a mae
            foreach ($lista as $k=>$entrada) {
                $relImg = "";
                switch ($entrada[mensagem]->relacao) {
                    case "concordo":
                        $relImg = "<img src=\"$urlimagens/ok.gif\" height=\"15\" width=\"15\">";
                        break;
                    case "discordo":
                        $relImg = "<img src=\"$urlimagens/notok.gif\" height=\"15\" width=\"15\">";
                        break;
                    case "duvida":
                        $relImg = "<img src=\"$urlimagens/hein.gif\" height=\"15\" width=\"15\">";
                        break;
                    case "ideia":
                        $relImg = "<img src=\"$urlimagens/idea.gif\" height=\"15\" width=\"15\">";
                        break;
                }
                
        //ve se a mensagem ja foi lida ou nao
                $chaveMen[] = opVal("codUser",$_SESSION[usuario]->codUser);
                $lidas = new RDLista("RDMensagensLidas", $chave);
                $jaLida = "0";
                if ($lidas->records != "0") {
                    foreach($lidas->records as $lida) {
                        if ($lida->codMensagem == $entrada[mensagem]->codMensagem) {
                            $jaLida = "1";
                        }
                    }
                }
                if ($jaLida == "1") {
                    $carta = "<img src=\"$urlimagens/open_envelope.gif\" height=\"11\" width=\"14\">";
                }
                else {
                    $carta = "<img src=\"$urlimagens/closed_envelope.gif\" height=\"11\" width=\"14\">";
                    $jaLida = "0";
                }
                
                
	//define o link para editar a mensagem
                $link = "<a href=\"manipula_men.php?forum=".$entrada[mensagem]->codForum."&mensagem=".$entrada[mensagem]->codMensagem."&jaLida=".$jaLida."&modo=".$modo."\" class=\"comum\">";

	//define a distancia da borda do box de acordo com o numero de pais da mensagem
                $tabulacao = " ";
                if ($entrada[geracao] != "0") {
                    $tabulacao = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $entrada[geracao])."<img src=\"$urlimagens/ico_forun_seta.gif\">";
                }

	//define o copro da mensagem (quando ela eh mostrada)
                if ($modo == "toda") {
                    $entradacorpo = "<br><table width=\"100%\"><tr><td width=\"".(($entrada[geracao] * 30)+10)."\">".str_repeat("&nbsp;",$entrada[geracao])."</td><td><table width=\"100%\"><tr><td width=\"100%\" align=justify><i><font size=-1>".$entrada[mensagem]->desCorpo."</font></i></td></tr>";
                    $imagens = $entrada[mensagem]->listaImagens();
                    if (!empty($imagens->records)) {
                        $entradacorpo .= "<tr><td width=\"100%\" align=center>";
                        foreach ($imagens->records as $imagem) {
                            $entradacorpo .= "<img src=\"$url/imagem.php?imagem=".$imagem->codArquivo."\">";
                        }
                        $entradacorpo .= "</td></tr>";
                    }
                    $entradacorpo .= "</table></td></tr></table>";
                }
                else {
                    $entradacorpo = " ";
                }


	//adiciona a mensagem vendo se ela foi enviada agora ou nao
                if ($_REQUEST[enviada] == $entrada[mensagem]->codMensagem) {
                    $tabela->addItem("<tr><td class=\"comum\">".$tabulacao."&nbsp;<font color=\"FF0000\"><b><i>".$link.$entrada[mensagem]->strTitulo."</a>&nbsp;".$relImg."&nbsp;(".$entrada[mensagem]->nomPessoa.", ".date("j/n/Y",$entrada[mensagem]->tempo).")&nbsp;</b></i></font>".$carta.$entradacorpo."</td></tr>");
                }
                else {
                    $tabela->addItem("<tr><td class=\"comum\">".$tabulacao.$link.$entrada[mensagem]->strTitulo."</a>&nbsp;".$relImg."&nbsp;(".$entrada[mensagem]->nomPessoa.", ".date("j/n/Y",$entrada[mensagem]->tempo).")&nbsp;".$carta.$entradacorpo."</td></tr>");
                }
                $cont++;
            }
        }
    }
}
else $tabela->add ("<font class=\"comum\"><i>$lang[nenhum_item]</i></font>");

$tabela->add ("</table>");
$pag->add($tabela);
$pag->add("<br>");
$pag->imprime();


?>
